<?php

namespace App\Http\Livewire\Backend\Settings;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Setting;

class Configuration extends Component {

    use WithFileUploads;

    /**
     * Components State
     */
    public $state = [
        'numbers_per_page' => -1,
        'messages_per_page' => 15,
        'widget_allowed_domains' => '',
        'blocked_keywords' => '',
        'cron_password' => '',
        'recaptcha' => [
            'enabled' => false,
            'site_key' => '',
            'secret_key' => ''
        ],
        'currency' => [
            'symbol' => '₹',
            'code' => 'inr'
        ],
        'auth_key' => '',
        'captcha' => 'off',
        'recaptcha2' => [],
        'recaptcha3' => [],
        'hcaptcha' => [],
        'min_recharge' => 5,
    ];

    public function mount() {
        foreach ($this->state as $key => $props) {
            $this->state[$key] = config('app.settings.' . $key);
        }
    }

    public function updated() {
        $this->dispatchBrowserEvent('componentUpdated');
    }

    public function update() {
        $this->dispatchBrowserEvent('componentUpdated');
        $this->validate(
            [
                'state.numbers_per_page' => 'required',
                'state.messages_per_page' => 'required',
                'state.currency.symbol' => 'required',
                'state.currency.code' => 'required',
                'state.cron_password' => 'required',
                'state.auth_key' => 'required',
                'state.min_recharge' => 'required',
            ],
            [
                'state.numbers_per_page.required' => 'Numbers Per Page is Required',
                'state.messages_per_page.required' => 'Messages Per Page is Required',
                'state.currency.symbol.required' => 'Currency Symbol is Required',
                'state.currency.code.required' => 'Currency Code is Required',
                'state.cron_password.required' => 'CRON Password field is Required',
                'state.auth_key.required' => 'Auth Key field is Required',
                'state.min_recharge.required' => 'Min Recharge Amount is Required',
            ]
        );
        if ($this->state['captcha'] == 'recaptcha2') {
            $this->validate(
                [
                    'state.recaptcha2.site_key' => 'required',
                    'state.recaptcha2.secret_key' => 'required'
                ],
                [
                    'state.recaptcha2.site_key.required' => 'Site Key is Required',
                    'state.recaptcha2.secret_key.required' => 'Secret Key is Required'
                ]
            );
        }
        if ($this->state['captcha'] == 'recaptcha3') {
            $this->validate(
                [
                    'state.recaptcha3.site_key' => 'required',
                    'state.recaptcha3.secret_key' => 'required'
                ],
                [
                    'state.recaptcha3.site_key.required' => 'Site Key is Required',
                    'state.recaptcha3.secret_key.required' => 'Secret Key is Required'
                ]
            );
        }
        if ($this->state['captcha'] == 'hcaptcha') {
            $this->validate(
                [
                    'state.hcaptcha.site_key' => 'required',
                    'state.hcaptcha.secret_key' => 'required'
                ],
                [
                    'state.hcaptcha.site_key.required' => 'Site Key is Required',
                    'state.hcaptcha.secret_key.required' => 'Secret Key is Required'
                ]
            );
        }
        $settings = Setting::whereIn('key', ['currency', 'numbers_per_page', 'messages_per_page', 'widget_allowed_domains', 'blocked_keywords', 'cron_password', 'auth_key', 'captcha', 'recaptcha2', 'recaptcha3', 'hcaptcha', 'min_recharge'])->get();
        foreach ($settings as $setting) {
            $setting->value = serialize($this->state[$setting->key]);
            $setting->save();
        }
        $this->emit('saved');
    }

    public function render() {
        return view('backend.settings.configuration');
    }
}
