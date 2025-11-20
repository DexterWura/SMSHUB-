<?php

namespace App\Http\Livewire\Backend\Settings;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class General extends Component {

    use WithFileUploads;

    /**
     * Components State
     */
    public $state = [
        'name' => '',
        'colors' => [
            'primary' => '#000000',
            'secondary' => '#000000',
            'tertiary' => '#000000'
        ],
        'cookie' => [],
        'custom_logo' => '',
        'custom_favicon' => '',
        'font_family' => [
            'head' => 'Poppins',
            'body' => 'Poppins',
        ],
        'language' => 'en',
        'show_country_list' => false,
    ];

    public $logo, $favicon;

    public function mount() {
        foreach ($this->state as $key => $value) {
            $this->state[$key] = config('app.settings.' . $key);
        }
        if (Storage::exists('public/images/logo.png')) {
            $this->state['custom_logo'] = Storage::url('public/images/logo.png');
        }
        if (Storage::exists('public/images/favicon.png')) {
            $this->state['custom_favicon'] = Storage::url('public/images/favicon.png');
        }
    }

    public function update() {
        $this->validate(
            [
                'state.name' => 'required',
                'state.logo' => 'image|max:1024',
                'state.favicon' => 'image|max:1024',
                'state.font_family.head' => 'required',
                'state.font_family.body' => 'required',
            ],
            [
                'state.name.required' => 'App Name is Required',
                'state.logo.image' => 'Invalid Logo file',
                'state.logo.max' => 'Max Size is 1MB',
                'state.favicon.image' => 'Invalid Logo file',
                'state.favicon.max' => 'Max Size is 1MB',
                'state.font_family.head' => 'Heading Font Family is Required',
                'state.font_family.body' => 'Body Font Family is Required',
            ]
        );
        if ($this->logo) {
            $this->logo->storeAs('images', 'logo.png', 'public');
        }
        if ($this->favicon) {
            $this->favicon->storeAs('images', 'favicon.png', 'public');
        }
        $settings = Setting::whereIn('key', ['name', 'colors', 'cookie', 'font_family', 'logo', 'favicon', 'language', 'show_country_list'])->get();
        foreach ($settings as $setting) {
            if (in_array($setting->key, ['logo', 'favicon'])) {
                if (Storage::exists('public/images/' . $setting->key . '.png')) {
                    $setting->value = serialize('storage/images/' . $setting->key . '.png');
                }
            } else {
                $setting->value = serialize($this->state[$setting->key]);
            }
            $setting->save();
        }
        $this->emit('saved');
    }

    public function render() {
        return view('backend.settings.general');
    }
}
