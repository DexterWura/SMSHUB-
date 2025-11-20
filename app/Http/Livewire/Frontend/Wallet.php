<?php

namespace App\Http\Livewire\Frontend;

use App\Models\PaymentMethod;
use App\Services\RazorPay;
use App\Services\Stripe;
use App\Models\WalletTransaction;
use App\Services\CoinPayments;
use App\Services\Paystack;
use App\Services\Paynow;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Wallet extends Component {

    use WithPagination;

    public $amount = 0, $methods = [], $selected, $crypto, $error, $total = 0, $razorpay;
    protected $listeners = ['cancelOrder'];

    public function submit() {
        $this->amount = round($this->amount, 2);
        $this->validate(
            [
                'amount' => 'required|numeric|min:' . config('app.settings.min_recharge', 1) . '|max:999999',
                'selected' => 'required'
            ],
            [
                'selected.required' => 'Please select a Payment Method to proceed'
            ]
        );
        if ($this->selected == 'coinpayments') {
            $this->validate(
                [
                    'crypto' => 'required'
                ],
                [
                    'crypto.required' => 'Please select a Crypto Currency to proceed'
                ]
            );
        }
        if ($this->selected == 'stripe') {
            $stripe = Stripe::initiate($this->amount);
            if (isset($stripe['success'])) {
                return redirect($stripe['success']);
            } elseif (isset($stripe['error'])) {
                $this->error = $stripe['error'];
            }
        } else if ($this->selected == 'razorpay') {
            $razorpay = RazorPay::initiate($this->amount);
            if (isset($razorpay['success'])) {
                $this->razorpay = $razorpay['success'];
                $this->dispatchBrowserEvent('razorpay-process');
            } elseif (isset($razorpay['error'])) {
                $this->error = $razorpay['error'];
            }
        } else if ($this->selected == 'paypal') {
            session(['amount' => $this->amount]);
            return redirect()->route('wallet.initiate.paypal');
        } else if ($this->selected == 'coinpayments') {
            $coinpayments = CoinPayments::initiate($this->amount, $this->crypto);
            if (isset($coinpayments['success'])) {
                return redirect($coinpayments['success']);
            } elseif (isset($coinpayments['error'])) {
                $this->error = $coinpayments['error'];
            }
        } else if ($this->selected == 'paystack') {
            $paystack = Paystack::initiate($this->amount);
            if (isset($paystack['success'])) {
                return redirect($paystack['success']);
            } elseif (isset($paystack['error'])) {
                $this->error = $paystack['error'];
            }
        } else if ($this->selected == 'paynow') {
            $paynow = Paynow::initiate($this->amount);
            if (isset($paynow['success'])) {
                return redirect($paynow['success']);
            } elseif (isset($paynow['error'])) {
                $this->error = $paynow['error'];
            }
        }
    }

    public function cancelOrder($transaction_id) {
        $transaction = WalletTransaction::where('id', $transaction_id)->first();
        if ($transaction) {
            $transaction->status = 2;
            $transaction->save();
            $this->mount();
        }
    }

    public function mount() {
        $this->amount = config('app.settings.min_recharge', 1);
        $this->methods = PaymentMethod::where('is_active', true)->get();
        $this->total = WalletTransaction::where('user_id', Auth::id())->count();
    }

    public function render() {
        return view('themes.' . config('app.settings.theme') . '.components.wallet', [
            'transactions' => WalletTransaction::where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(15)
        ]);
    }
}
