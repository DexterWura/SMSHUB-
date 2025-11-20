<?php

namespace App\Services;

use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\WalletLog;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class Paynow {

    /**
     * Initialize Paynow payment
     * 
     * @param float $amount
     * @return array
     */
    public static function initiate($amount) {
        try {
            DB::beginTransaction();
            $user = User::findOrFail(Auth::id());
            [$integrationId, $integrationKey] = Paynow::getApiKeys();
            
            // Include Paynow SDK autoloader
            require_once app_path('Services/Paynow/autoloader.php');
            
            $transaction = new WalletTransaction;
            $transaction->user_id = $user->id;
            $transaction->payment_method = 'paynow';
            $transaction->status = 0;
            $transaction->save();
            
            // Create Paynow instance
            $paynow = new \Paynow\Payments\Paynow(
                $integrationId,
                $integrationKey,
                route('wallet.paynow.callback'), // Result URL (server-to-server callback)
                route('wallet.process', ['id' => $transaction->id, 'status' => 'process']) // Return URL (user redirect)
            );
            
            // Create payment
            $payment = $paynow->createPayment('Transaction #' . $transaction->id, $user->email);
            $payment->add('Add Funds to Wallet', $amount);
            
            // Send payment to Paynow
            $response = $paynow->send($payment);
            
            if ($response->success()) {
                $transaction->data = json_encode([
                    'poll_url' => $response->pollUrl(),
                    'redirect_url' => $response->redirectUrl(),
                    'amount' => $amount,
                    'reference' => 'Transaction #' . $transaction->id,
                ]);
                $transaction->save();
                DB::commit();
                return [
                    'success' => $response->redirectUrl()
                ];
            } else {
                DB::rollBack();
                return [
                    'error' => $response->error ?? __('Failed to initialize payment')
                ];
            }
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Process Paynow payment callback
     * 
     * @param int $id Transaction ID
     * @param string $status Status
     * @return array
     */
    public static function process($id, $status) {
        $transaction = WalletTransaction::find($id);
        if ($transaction && $transaction->status == 0) {
            if ($status == 'cancel') {
                $transaction->status = 2;
                $transaction->save();
                return [
                    'error' => __('Transaction Cancelled')
                ];
            } else if ($status == 'process') {
                try {
                    DB::beginTransaction();
                    [$integrationId, $integrationKey] = Paynow::getApiKeys();
                    
                    // Include Paynow SDK autoloader
                    require_once app_path('Services/Paynow/autoloader.php');
                    
                    // Create Paynow instance
                    $paynow = new \Paynow\Payments\Paynow(
                        $integrationId,
                        $integrationKey,
                        route('wallet.process', ['id' => $transaction->id, 'status' => 'process']),
                        route('wallet.process', ['id' => $transaction->id, 'status' => 'process'])
                    );
                    
                    $data = json_decode($transaction->data);
                    
                    if (!isset($data->poll_url)) {
                        throw new Exception('Poll URL not found');
                    }
                    
                    // Poll transaction status
                    $status = $paynow->pollTransaction($data->poll_url);
                    
                    if ($status->paid()) {
                        $amount = $data->amount;
                        $user = User::find($transaction->user_id);
                        $log = new WalletLog;
                        $log->user_id = $user->id;
                        $log->before = $user->wallet;
                        $log->after = $user->wallet + $amount;
                        $log->log = 'Added Funds on ' . Carbon::now();
                        $log->save();
                        $user->wallet = $user->wallet + $amount;
                        $user->save();
                        $transaction->status = 1;
                        $transaction->data = json_encode([
                            'poll_url' => $data->poll_url,
                            'redirect_url' => $data->redirect_url,
                            'amount' => $amount,
                            'status' => 'paid',
                            'reference' => $status->reference() ?? null,
                        ]);
                        $transaction->save();
                        DB::commit();
                        return [
                            'success' => __('Funds added successfully to your account')
                        ];
                    } else {
                        $transaction->status = 2;
                        $transaction->save();
                        DB::commit();
                        return [
                            'error' => __('Payment was not completed')
                        ];
                    }
                } catch (Exception $e) {
                    DB::rollBack();
                    $transaction->status = 2;
                    $transaction->save();
                    return [
                        'error' => $e->getMessage()
                    ];
                }
            }
        }
        return [
            'error' => __('Invalid Transaction')
        ];
    }

    /**
     * Get API keys from database
     * 
     * @return array
     */
    private static function getApiKeys() {
        $method = PaymentMethod::where('name', 'paynow')->first();
        if ($method && $method->keys) {
            return explode(':', Crypt::decrypt($method->keys));
        }
        throw new Exception('Paynow API keys not configured');
    }
}

