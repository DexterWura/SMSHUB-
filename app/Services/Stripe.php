<?php

namespace App\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\WalletLog;
use App\Models\WalletTransaction;

class Stripe {

    public static function initiate($amount) {
        try {
            DB::beginTransaction();
            \Stripe\Stripe::setApiKey(Stripe::getApiKey());
            $user = User::findOrFail(Auth::id());
            $transaction = new WalletTransaction;
            $transaction->user_id = $user->id;
            $transaction->payment_method = 'stripe';
            $transaction->status = 0;
            $transaction->save();
            $checkout_session = \Stripe\Checkout\Session::create([
                'customer_email' => $user->email,
                'line_items' => [[
                    'price_data' => [
                        'currency' => strtolower(config('app.settings.currency.code')),
                        'product_data' => [
                            'name' => 'Add Funds'
                        ],
                        'unit_amount_decimal' => $amount * 100
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('wallet.process', ['id' => $transaction->id, 'status' => 'success']),
                'cancel_url' => route('wallet.process', ['id' => $transaction->id, 'status' => 'cancel']),
            ]);
            $transaction->data = json_encode($checkout_session);
            $transaction->save();
            DB::commit();
            return [
                'success' => $checkout_session->url
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
            } else if ($status == 'success') {
                $data = json_decode($transaction->data);
                $stripe = new \Stripe\StripeClient(
                    Stripe::getApiKey()
                );
                $response = $stripe->checkout->sessions->retrieve(
                    $data->id,
                    []
                );
                if ($response->payment_status == 'paid' && $transaction->status == 0) {
                    try {
                        DB::beginTransaction();
                        $amount = $response->amount_total / 100;
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
                        'error' => __('Possible fruad detected. Report sent to Admin')
                    ];
                }
            }
        }
        return [
            'error' => __('Invalid Transaction')
        ];
    }

    private static function getApiKey() {
        $method = PaymentMethod::where('name', 'stripe')->first();
        if ($method->keys) {
            return Crypt::decrypt($method->keys);
        }
    }
}
