<?php

namespace App\Http\Controllers;

use App\Services\RazorPay;
use App\Services\Stripe;
use App\Models\WalletTransaction;
use App\Services\CoinPayments;
use App\Services\PayPal;
use App\Services\Paystack;
use App\Services\Paynow;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller {

    public function initiatePayPal(Request $request) {
        if (session('amount', 0) == 0) {
            return redirect()->route('wallet');
        }
        $paypal = PayPal::initiate(session('amount'));
        $request->session()->forget('amount');
        return view('common.paypal')->with(compact('paypal'));
    }

    public function process($id, $status, Request $request) {
        $transaction = WalletTransaction::findOrFail($id);
        if ($transaction->payment_method == 'stripe') {
            $process = Stripe::process($id, $status);
            if (isset($process['success'])) {
                Session::flash('success', $process['success']);
            } else if (isset($process['error'])) {
                Session::flash('error', $process['error']);
            }
            return redirect()->route('wallet');
        } else if ($transaction->payment_method == 'razorpay') {
            $process = RazorPay::process($id, $request->get('razorpay_payment_id'), $request->get('razorpay_signature'));
            if (isset($process['success'])) {
                Session::flash('success', $process['success']);
            } else if (isset($process['error'])) {
                Session::flash('error', $process['error']);
            }
            return redirect()->route('wallet');
        } else if ($transaction->payment_method == 'paypal') {
            $process = PayPal::process($id, $status, $request->get('paypal_order_id'));
            if (isset($process['success'])) {
                Session::flash('success', $process['success']);
            } else if (isset($process['error'])) {
                Session::flash('error', $process['error']);
            }
            return redirect()->route('wallet');
        } else if ($transaction->payment_method == 'coinpayments') {
            $process = CoinPayments::process($id, $status);
            if (isset($process['success'])) {
                Session::flash('success', $process['success']);
            } else if (isset($process['error'])) {
                Session::flash('error', $process['error']);
            }
            return redirect()->route('wallet');
        } else if ($transaction->payment_method == 'paystack') {
            $process = Paystack::process($id, $status);
            if (isset($process['success'])) {
                Session::flash('success', $process['success']);
            } else if (isset($process['error'])) {
                Session::flash('error', $process['error']);
            }
            return redirect()->route('wallet');
        } else if ($transaction->payment_method == 'paynow') {
            $process = Paynow::process($id, $status);
            if (isset($process['success'])) {
                Session::flash('success', $process['success']);
            } else if (isset($process['error'])) {
                Session::flash('error', $process['error']);
            }
            return redirect()->route('wallet');
        }
    }

    /**
     * Handle Paynow callback (server-to-server)
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function paynowCallback(Request $request) {
        try {
            require_once app_path('Services/Paynow/autoloader.php');
            
            $method = PaymentMethod::where('name', 'paynow')->first();
            if (!$method || !$method->keys) {
                return response('Error: API keys not configured', 500);
            }
            
            [$integrationId, $integrationKey] = explode(':', Crypt::decrypt($method->keys));
            
            $paynow = new \Paynow\Payments\Paynow(
                $integrationId,
                $integrationKey,
                route('wallet.process', ['id' => 0, 'status' => 'process']),
                route('wallet.process', ['id' => 0, 'status' => 'process'])
            );
            
            $status = $paynow->processStatusUpdate();
            
            if ($status->paid()) {
                // Find transaction by reference
                $reference = $status->reference();
                // Reference format is "Transaction #ID"
                if (preg_match('/Transaction #(\d+)/', $reference, $matches)) {
                    $transactionId = $matches[1];
                    $transaction = WalletTransaction::where('id', $transactionId)
                        ->where('payment_method', 'paynow')
                        ->where('status', 0)
                        ->first();
                    
                    if ($transaction) {
                        // Process the payment
                        Paynow::process($transaction->id, 'process');
                    }
                }
            }
            
            // Return OK to Paynow
            return response('OK', 200);
        } catch (\Exception $e) {
            Log::error('Paynow callback error: ' . $e->getMessage());
            return response('Error', 500);
        }
    }
}
