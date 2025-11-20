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

class Paystack {

    public static function initiate($amount) {
        try {
            DB::beginTransaction();
            $user = User::findOrFail(Auth::id());
            [$publicKey, $secretKey] = Paystack::getApiKeys();
            $transaction = new WalletTransaction;
            $transaction->user_id = $user->id;
            $transaction->payment_method = 'paystack';
            $transaction->status = 0;
            $transaction->save();
            $response = Http::withToken($secretKey)->accept('application/json')->post('https://api.paystack.co/transaction/initialize', [
                'amount' => $amount * 100,
                'email' => $user->email,
                'callback_url' => route('wallet.process', ['id' => $transaction->id, 'status' => 'process']),
            ]);
            $data = $response->object();
            $transaction->data = json_encode($data);
            $transaction->save();
            DB::commit();
            return [
                'success' => $data->data->authorization_url
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage()
            ];
        }
    }

    public static function process($id, $status) {
        $transaction = WalletTransaction::find($id);
        if ($transaction && $transaction->status == 0) {
            if ($status == 'cancel') {
                $transaction->status = 2;
                $transaction->save();
                return [
                    'error' => 'Transaction Cancelled'
                ];
            } else if ($status == 'process') {
                $data = json_decode($transaction->data);
                [$publicKey, $secretKey] = Paystack::getApiKeys();
                $response = Http::withToken($secretKey)->get('https://api.paystack.co/transaction/verify/' . $data->data->reference);
                $data = $response->object();
                if ($data->data->status == 'success' && $transaction->status == 0) {
                    try {
                        DB::beginTransaction();
                        $amount = $data->data->amount / 100;
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
                        $transaction->data = json_encode($response);
                        $transaction->save();
                        DB::commit();
                        return [
                            'success' => __('Funds added successfully to your account')
                        ];
                    } catch (Exception $e) {
                        DB::rollBack();
                        return [
                            'error' => $e->getMessage()
                        ];
                    }
                } else {
                    $transaction->status = 3;
                    $transaction->save();
                    return [
                        'error' => __('Payment Failed')
                    ];
                }
            }
        }
        return [
            'error' => __('Invalid Transaction')
        ];
    }

    private static function getApiKeys() {
        $method = PaymentMethod::where('name', 'paystack')->first();
        if ($method->keys) {
            return explode(':', Crypt::decrypt($method->keys));
        }
    }
}
