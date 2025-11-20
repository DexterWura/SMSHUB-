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
use stdClass;

class CoinPayments {

    public static function initiate($amount, $currency = 'btc') {
        try {
            DB::beginTransaction();
            $user = User::findOrFail(Auth::id());
            $transaction = new WalletTransaction;
            $transaction->user_id = $user->id;
            $transaction->payment_method = 'coinpayments';
            $transaction->status = 0;
            $transaction->save();
            $coinpayments = CoinPayments::api('create_transaction', [
                'amount' => $amount,
                'currency1' => strtolower(config('app.settings.currency.code')),
                'currency2' => $currency,
                'buyer_email' => $user->email,
                'ipn_url' => route('wallet.process.coinpayments', ['id' => $transaction->id]),
                'success_url' => route('wallet.process', ['id' => $transaction->id, 'status' => 'success']),
                'cancel_url' => route('wallet.process', ['id' => $transaction->id, 'status' => 'cancel']),
            ]);
            $coinpayments['amount'] = $amount;
            $transaction->data = json_encode($coinpayments);
            $transaction->save();
            if ($coinpayments['result']['checkout_url']) {
                DB::commit();
                return [
                    'success' => $coinpayments['result']['checkout_url']
                ];
            } else {
                $transaction->status = 2;
                $transaction->save();
                DB::commit();
                return [
                    'error' => $coinpayments['error']
                ];
            }
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
            } else {
                $data = json_decode($transaction->data);
                $response = CoinPayments::api('get_tx_info', [
                    'txid' => $data->result->txn_id
                ]);
                if ($response['error'] == 'ok') {
                    if ($response['result']['amount'] == $response['result']['received']) {
                        try {
                            DB::beginTransaction();
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
                    }
                }
            }
        }
        return [
            'error' => __('Invalid Transaction')
        ];
    }

    public static function updateCoins() {
        $coinpayments = PaymentMethod::where('name', 'coinpayments')->first();
        $coins = CoinPayments::api('rates', [
            'short' => 1,
            'accepted' => 2
        ]);
        $metadata = json_decode($coinpayments->metadata);
        if (!is_object($metadata)) {
            $metadata = new stdClass;
        }
        if (isset($coins['result'])) {
            $data = [];
            foreach ($coins['result'] as $key => $value) {
                if ($value['is_fiat'] == 0) {
                    array_push($data, $key);
                }
            }
            $metadata->coins = $data;
            $coinpayments->metadata = json_encode($metadata);
            $coinpayments->save();
        }
    }

    private static function getApiKeys() {
        $method = PaymentMethod::where('name', 'coinpayments')->first();
        if ($method->keys) {
            return explode(':', Crypt::decrypt($method->keys));
        }
    }

    private static function api($cmd, $req = array()) {
        // Fill these in from your API Keys page
        [$public_key, $private_key] = CoinPayments::getApiKeys();

        // Set the API command and required fields
        $req['version'] = 1;
        $req['cmd'] = $cmd;
        $req['key'] = $public_key;
        $req['format'] = 'json'; //supported values are json and xml

        // Generate the query string
        $post_data = http_build_query($req, '', '&');

        // Calculate the HMAC signature on the POST data
        $hmac = hash_hmac('sha512', $post_data, $private_key);

        // Create cURL handle and initialize (if needed)
        static $ch = NULL;
        if ($ch === NULL) {
            $ch = curl_init('https://www.coinpayments.net/api.php');
            curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('HMAC: ' . $hmac));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

        // Execute the call and close cURL handle
        $data = curl_exec($ch);
        // Parse and return data if successful.
        if ($data !== FALSE) {
            if (PHP_INT_SIZE < 8 && version_compare(PHP_VERSION, '5.4.0') >= 0) {
                // We are on 32-bit PHP, so use the bigint as string option. If you are using any API calls with Satoshis it is highly NOT recommended to use 32-bit PHP
                $dec = json_decode($data, TRUE, 512, JSON_BIGINT_AS_STRING);
            } else {
                $dec = json_decode($data, TRUE);
            }
            if ($dec !== NULL && count($dec)) {
                return $dec;
            } else {
                // If you are using PHP 5.5.0 or higher you can use json_last_error_msg() for a better error message
                return array('error' => 'Unable to parse JSON result (' . json_last_error() . ')');
            }
        } else {
            return array('error' => 'cURL error: ' . curl_error($ch));
        }
    }
}
