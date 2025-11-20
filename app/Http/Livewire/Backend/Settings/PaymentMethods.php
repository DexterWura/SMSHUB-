<?php

namespace App\Http\Livewire\Backend\Settings;

use App\Models\PaymentMethod;
use App\Services\CoinPayments;
use Illuminate\Support\Facades\Crypt;
use Livewire\Component;

class PaymentMethods extends Component {

    public $view = 'methods', $methods;

    public $stripe = [
        'is_active' => false,
        'key' => null
    ];

    public $razorpay = [
        'is_active' => false,
        'key_id' => null,
        'key_secret' => null
    ];

    public $paypal = [
        'is_active' => false,
        'client_id' => null,
        'client_secret' => null
    ];

    public $coinpayments = [
        'is_active' => false,
        'public_key' => null,
        'private_key' => null
    ];

    public $paystack = [
        'is_active' => false,
        'public_key' => null,
        'secret_key' => null
    ];

    public function save() {
        if ($this->view == 'stripe') {
            $stripe = PaymentMethod::where('name', 'stripe')->first();
            $stripe->is_active = $this->stripe['is_active'];
            $stripe->keys = Crypt::encrypt($this->stripe['key']);
            $stripe->save();
            $this->emit('saved');
        } else if ($this->view == 'razorpay') {
            $razorpay = PaymentMethod::where('name', 'razorpay')->first();
            $razorpay->is_active = $this->razorpay['is_active'];
            $razorpay->keys = Crypt::encrypt($this->razorpay['key_id'] . ':' . $this->razorpay['key_secret']);
            $razorpay->save();
            $this->emit('saved');
        } else if ($this->view == 'paypal') {
            $paypal = PaymentMethod::where('name', 'paypal')->first();
            $paypal->is_active = $this->paypal['is_active'];
            $paypal->keys = Crypt::encrypt($this->paypal['client_id'] . ':' . $this->paypal['client_secret']);
            $paypal->save();
            $this->emit('saved');
        } else if ($this->view == 'coinpayments') {
            $coinpayments = PaymentMethod::where('name', 'coinpayments')->first();
            $coinpayments->is_active = $this->coinpayments['is_active'];
            $coinpayments->keys = Crypt::encrypt($this->coinpayments['public_key'] . ':' . $this->coinpayments['private_key']);
            $coinpayments->save();
            CoinPayments::updateCoins();
            $this->emit('saved');
        } else if ($this->view == 'paystack') {
            $paystack = PaymentMethod::where('name', 'paystack')->first();
            $paystack->is_active = $this->paystack['is_active'];
            $paystack->keys = Crypt::encrypt($this->paystack['public_key'] . ':' . $this->paystack['secret_key']);
            $paystack->save();
            $this->emit('saved');
        }
        $this->mount();
    }

    public function mount() {
        $this->methods = PaymentMethod::get();
        foreach ($this->methods as $method) {
            if ($method->name == 'stripe') {
                $this->stripe['is_active'] = $method->is_active ? true : false;
                if ($method->keys) {
                    $this->stripe['key'] = Crypt::decrypt($method->keys);
                }
            } else if ($method->name == 'razorpay') {
                $this->razorpay['is_active'] = $method->is_active ? true : false;
                if ($method->keys) {
                    $keys = explode(':', Crypt::decrypt($method->keys));
                    if (count($keys) == 2) {
                        $this->razorpay['key_id'] = $keys[0];
                        $this->razorpay['key_secret'] = $keys[1];
                    }
                }
            } else if ($method->name == 'paypal') {
                $this->paypal['is_active'] = $method->is_active ? true : false;
                if ($method->keys) {
                    $keys = explode(':', Crypt::decrypt($method->keys));
                    if (count($keys) == 2) {
                        $this->paypal['client_id'] = $keys[0];
                        $this->paypal['client_secret'] = $keys[1];
                    }
                }
            } else if ($method->name == 'coinpayments') {
                $this->coinpayments['is_active'] = $method->is_active ? true : false;
                if ($method->keys) {
                    $keys = explode(':', Crypt::decrypt($method->keys));
                    if (count($keys) == 2) {
                        $this->coinpayments['public_key'] = $keys[0];
                        $this->coinpayments['private_key'] = $keys[1];
                    }
                }
            } else if ($method->name == 'paystack') {
                $this->paystack['is_active'] = $method->is_active ? true : false;
                if ($method->keys) {
                    $keys = explode(':', Crypt::decrypt($method->keys));
                    if (count($keys) == 2) {
                        $this->paystack['public_key'] = $keys[0];
                        $this->paystack['secret_key'] = $keys[1];
                    }
                }
            }
        }
    }

    public function render() {
        return view('backend.settings.payment-methods');
    }

    public function updateCoinPaymentsCoins() {
        CoinPayments::updateCoins();
    }
}
