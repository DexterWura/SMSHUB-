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
use Illuminate\Support\Facades\Http;

class PayPal {

    public static function initiate($amount) {
        $user = User::findOrFail(Auth::id());
        $transaction = new WalletTransaction;
        $transaction->user_id = $user->id;
        $transaction->payment_method = 'paypal';
        $transaction->data = '{"amount": ' . $amount . '}';
        $transaction->status = 0;
        $transaction->save();
        [$clientId, $clientSecret] = PayPal::getApiKeys();
        $paypal = [
            'client_id' => $clientId,
            'amount' => $amount,
            'currency' => strtoupper(config('app.settings.currency.code')),
            'transaction_id' => $transaction->id,
        ];
        return $paypal;
    }

    public static function process($id, $status, $order_id) {
        $transaction = WalletTransaction::find($id);
        if ($transaction && $transaction->status == 0) {
            if ($status == 'cancel' || $status == 'error') {
                $transaction->status = 2;
                $transaction->save();
                if ($status == 'cancel') {
                    return [
                        'error' => __('Transaction Cancelled')
                    ];
                } else {
                    return [
                        'error' => __('Transaction failed due to error. Please try again later.')
                    ];
                }
            }
            try {
                DB::beginTransaction();
                [$clientId, $clientSecret] = PayPal::getApiKeys();
                $response = Http::withBasicAuth($clientId, $clientSecret)->get('https://api-m.sandbox.paypal.com/v2/checkout/orders/' . $order_id);
                $paypal = $response->object();
                if ($paypal && isset($paypal->status) && $paypal->status == 'COMPLETED' && isset($paypal->purchase_units[0]->payments->captures[0]->status) && $paypal->purchase_units[0]->payments->captures[0]->status == 'COMPLETED') {
                    $payment = $paypal->purchase_units[0]->payments->captures[0];
                    $user = User::find($transaction->user_id);
                    $log = new WalletLog;
                    $log->user_id = $user->id;
                    $log->before = $user->wallet;
                    $log->after = $user->wallet + $payment->amount->value;
                    $log->log = 'Added Funds on ' . Carbon::now();
                    $log->save();
                    $user->wallet = $user->wallet + $payment->amount->value;
                    $user->save();
                    $paypal->amount = $payment->amount->value;
                    $transaction->data = json_encode($paypal);
                    $transaction->status = 1;
                    $transaction->save();
                    DB::commit();
                    return [
                        'success' => __('Funds added successfully to your account')
                    ];
                } else {
                    throw new Exception('Incomplete Transaction');
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
        return [
            'error' => __('Invalid Transaction')
        ];
    }

    private static function getApiKeys() {
        $method = PaymentMethod::where('name', 'paypal')->first();
        if ($method->keys) {
            return explode(':', Crypt::decrypt($method->keys));
        }
    }
}
