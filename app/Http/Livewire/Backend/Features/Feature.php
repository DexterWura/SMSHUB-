<?php

namespace App\Http\Livewire\Backend\Features;

use App\Models\Feature as ModelsFeature;
use Livewire\Component;

class Feature extends Component {

    public $features, $feature, $addFeature, $updateFeature;

    public function mount() {
        $this->updateFeatures();
        $this->clearFeatureObject();
        $this->addFeature = false;
        $this->updateFeature = false;
    }

    public function updateFeatures() {
        $this->features = ModelsFeature::where('lang', null)->get();
    }

    public function clearAddUpdate() {
        if (isset($this->feature['lang'])) {
            unset($this->feature['lang']);
            $this->showUpdate($this->feature['translate_parent_id']);
        } else {
            $this->addFeature = false;
            $this->updateFeature = false;
            $this->clearFeatureObject();
        }
    }

    public function clearFeatureObject() {
        $this->feature = [
            'title' => '',
            'description' => '',
            'icon' => ''
        ];
    }

    public function add() {
        $this->validate(
            [
                'feature.title' => 'required',
                'feature.description' => 'required',
                'feature.icon' => 'required',
            ],
            [
                'feature.title.required' => 'Feature Title is Required',
                'feature.description.required' => 'Please enter some Content for the Feature',
                'feature.icon.required' => 'Feature icon is Required',
            ]
        );
        ModelsFeature::create($this->feature);
        $this->emit('saved');
        $this->updateFeatures();
        $this->clearAddUpdate();
    }

    public function showUpdate($feature_id) {
        $this->updateFeature = true;
        $this->feature = ModelsFeature::find($feature_id)->toArray();
    }

    public function update() {
        $this->validate(
            [
                'feature.title' => 'required',
                'feature.description' => 'required',
                'feature.icon' => 'required',
            ],
            [
                'feature.title.required' => 'Feature Title is Required',
                'feature.description.required' => 'Please enter some Content for the Feature',
                'feature.icon.required' => 'Feature icon is Required',
            ]
        );
        $feature = ModelsFeature::findOrFail($this->feature['id']);
        if (isset($this->feature['lang'])) {
            $feature = ModelsFeature::where('translate_parent_id', $this->feature['translate_parent_id'])->where('lang', $this->feature['lang'])->first();
            if (!$feature) {
                $feature = new ModelsFeature;
                $this->feature['translate_parent_id'] = $this->feature['id'];
            }
        }
        $feature->fill($this->feature);
        $feature->save();
        $this->emit('saved');
    }

    public function translate($locale) {
        $feature = ModelsFeature::where('translate_parent_id', $this->feature['id'])->where('lang', $locale)->first();
        if ($feature) {
            $this->showUpdate($feature->id);
        }
        $this->feature['lang'] = $locale;
        $this->feature['lang_text'] = config('app.locales_text')[array_search($locale, config('app.locales'))];
        $this->dispatchBrowserEvent('componentUpdated');
    }

    public function isTranslated($locale) {
        if (ModelsFeature::where('translate_parent_id', $this->feature['id'])->where('lang', $locale)->count() > 0) {
            return true;
        }
        return false;
    }

    public function delete($feature_id) {
        $feature = ModelsFeature::findOrFail($feature_id);
        $feature->delete();
        ModelsFeature::where('translate_parent_id', $feature->id)->delete();
        $this->updateFeatures();
    }

    public function render() {
        return view('backend.features.feature');
    }
}
