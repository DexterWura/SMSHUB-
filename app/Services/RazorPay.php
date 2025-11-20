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
use Razorpay\Api\Api;

class RazorPay {

    public static function initiate($amount) {
        try {
            DB::beginTransaction();
            $user = User::findOrFail(Auth::id());
            $transaction = new WalletTransaction;
            $transaction->user_id = $user->id;
            $transaction->payment_method = 'razorpay';
            $transaction->status = 0;
            $transaction->save();
            [$keyId, $keySecret] = RazorPay::getApiKeys();
            $api = new Api($keyId, $keySecret);
            $order = $api->order->create([
                'receipt' => $transaction->id,
                'amount' => $amount * 100,
                'currency' => 'INR',
                'payment_capture' => 1
            ]);
            $transaction->data = json_encode([
                'order_id' => $order['id'],
                'amount' => $amount * 100,
            ]);
            $transaction->save();
            $data = [
                'key' => $keyId,
                'amount' => $amount * 100,
                'name' => config('app.settings.name'),
                'description' => 'Add Funds',
                'image' => config('app.url') . '/' . config('app.settings.logo'),
                'prefill' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'theme' => [
                    'color' => config('app.settings.colors.primary')
                ],
                'order_id' => $order['id'],
                'transaction_id' => $transaction->id
            ];
            DB::commit();
            return [
                'success' => json_encode($data)
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    public static function process($id, $paymentId, $signature) {
        $transaction = WalletTransaction::find($id);
        if ($transaction && $transaction->status == 0) {
            try {
                DB::beginTransaction();
                [$keyId, $keySecret] = RazorPay::getApiKeys();
                $api = new Api($keyId, $keySecret);
                $data = json_decode($transaction->data);
                $attributes = array(
                    'razorpay_order_id' => $data->order_id,
                    'razorpay_payment_id' => $paymentId,
                    'razorpay_signature' => $signature
                );
                $api->utility->verifyPaymentSignature($attributes);
                $amount = $data->amount / 100;
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
                $transaction->save();
                DB::commit();
                return [
                    'success' => __('Funds added successfully to your account')
                ];
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
        $method = PaymentMethod::where('name', 'razorpay')->first();
        if ($method->keys) {
            return explode(':', Crypt::decrypt($method->keys));
        }
    }
}
