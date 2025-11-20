<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Assign;
use App\Models\Message;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\WithPagination;

class Number extends Component {

    use WithPagination;

    public $number, $is_admin = false, $auto_renew = null, $captchaError, $captcha, $timestamp;

    protected $listeners = ['setCaptcha', 'checkReCaptcha3'];

    public function mount($number) {
        if (config('app.settings.captcha', 'off') == 'off') {
            session(['timestamp' => time() + 900]);
        }
        $this->timestamp = session('timestamp', 0);
        if (Auth::check() && Auth::user()->role == 7) {
            $this->is_admin = true;
        }
        if (!is_object($number)) {
            return abort(404);
        }
        $this->number = $number;
        if ($this->number->type == 1 && !Auth::check()) {
            return abort(404);
        }
        if ($this->number->type == 2 && !$this->isAssigned($this->number->id)) {
            return abort(404);
        }
        if ($this->number->type == 3 || $this->number->type == 4) {
            $order = $this->order($this->number->id);
            if ($order->auto_renew) {
                $this->auto_renew = true;
            } else {
                $this->auto_renew = false;
            }
        }
    }

    public function render() {
        $query = Message::where('to', 'like', '%' . $this->number->number . '%');
        foreach (explode(',', config('app.settings.blocked_keywords')) as $keyword) {
            if (trim($keyword)) {
                $query->where('msg', 'not like', '%' . trim($keyword) . '%');
            }
        }
        return view('themes.' . config('app.settings.theme') . '.components.number')->with([
            'messages' => $query->orderBy('created_at', 'DESC')->paginate(config('app.settings.messages_per_page', 15))
        ]);
    }

    public function updatedAutoRenew($value) {
        $order = Order::where('number_id', $this->number->id)->where('user_id', Auth::id())->where('expiry', '>', Carbon::now())->first();
        $order->auto_renew = $value;
        $order->save();
    }

    public function setCaptcha($response) {
        $this->captcha = $response;
        if ($this->validateCaptcha()) {
            session(['timestamp' => time() + 900]);
            $this->mount($this->number);
            return '';
        }
    }

    public function checkReCaptcha3($token) {
        if ($this->timestamp < time()) {
            $response = Http::post('https://www.google.com/recaptcha/api/siteverify?secret=' . config('app.settings.recaptcha3.secret_key') . '&response=' . $token);
            $data = $response->json();
            if ($data['success']) {
                $captcha = $data['score'];
                if ($captcha > 0.5) {
                    session(['timestamp' => time() + 900]);
                    $this->mount($this->number);
                } else {
                    return $this->captchaError = __('Captcha Failed! Please try again');
                }
            } else {
                return $this->captchaError = __('Captcha Failed! Error: ') . json_encode($data['error-codes']);
            }
        }
    }

    /**
     * Validate Captcha
     */
    private function validateCaptcha() {
        if (config('app.settings.captcha') == 'hcaptcha') {
            $response = Http::asForm()->post('https://hcaptcha.com/siteverify', [
                'response' => $this->captcha,
                'secret' => config('app.settings.hcaptcha.secret_key')
            ])->object();
            return $response->success;
        } else if (config('app.settings.captcha') == 'recaptcha2') {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'response' => $this->captcha,
                'secret' => config('app.settings.recaptcha2.secret_key')
            ])->object();
            return $response->success;
        }
        return true;
    }

    private function isAssigned($number_id) {
        return Assign::where('number_id', $number_id)->where('user_id', Auth::id())->count() > 0;
    }

    private function order($number_id) {
        return Order::where('number_id', $number_id)->where('user_id', Auth::id())->where('expiry', '>', Carbon::now())->first();
    }
}
