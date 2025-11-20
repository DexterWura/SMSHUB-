<?php

namespace App\Http\Livewire\Backend\Settings;

use Livewire\Component;
use App\Models\Setting;

class Keywords extends Component {

    /**
     * Components State
     */
    public $state = [
        'keywords' => [
            'to' => '',
            'from' => '',
            'msg' => '',
            'uuid' => '',
        ]
    ];

    public function mount() {
        $this->state['keywords'] = config('app.settings.keywords');
    }

    public function update() {
        $setting = Setting::where('key', 'keywords')->first();
        $setting->value = serialize($this->state['keywords']);
        $setting->save();
        $this->emit('saved');
    }

    public function render() {
        return view('backend.settings.keywords');
    }
}
