<?php

namespace App\Http\Livewire\Backend\Settings;

use Livewire\Component;
use App\Models\Setting;

class Advance extends Component {
    /**
     * Components State
     */
    public $state = [
        'homepage' => [
            'header' => '',
            'footer' => '',
        ],
        'global' => [
            'css' => '',
            'js' => '',
            'header' => '',
            'footer' => ''
        ]
    ];

    public function mount() {
        $this->state['global'] = config('app.settings.global');
        $this->state['homepage'] = config('app.settings.homepage');
    }

    public function update() {
        $settings = Setting::whereIn('key', ['homepage', 'global'])->get();
        foreach ($settings as $setting) {
            $setting->value = serialize($this->state[$setting->key]);
            $setting->save();
        }
        $this->emit('saved');
    }

    public function render() {
        return view('backend.settings.advance');
    }
}
