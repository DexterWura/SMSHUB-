<?php

namespace App\Http\Livewire\Backend\Sections;

use App\Models\Section as ModelsSection;
use Livewire\Component;

class Section extends Component {

    public $sections, $section, $addSection, $updateSection;

    public function mount() {
        $this->updateSections();
        $this->clearSectionObject();
        $this->addSection = false;
        $this->updateSection = false;
    }

    public function updateSections() {
        $this->sections = ModelsSection::where('lang', null)->get();
    }

    public function clearAddUpdate() {
        if (isset($this->section['lang'])) {
            unset($this->section['lang']);
            $this->showUpdate(ModelsSection::where('slug', $this->section['slug'])->where('lang', null)->first()->id);
        } else {
            $this->addSection = false;
            $this->updateSection = false;
            $this->clearSectionObject();
            $this->dispatchBrowserEvent('clearQuill');
        }
        $this->dispatchBrowserEvent('componentUpdated');
    }

    public function clearSectionObject() {
        $this->section = [
            'title' => '',
            'content' => '',
            'slug' => ''
        ];
    }

    public function add() {
        $this->dispatchBrowserEvent('componentUpdated');
        $this->validate(
            [
                'section.title' => 'required',
                'section.content' => 'required',
                'section.slug' => 'required',
            ],
            [
                'section.title.required' => 'Section Title is Required',
                'section.content.required' => 'Please enter some Content for the Section',
                'section.slug.required' => 'Section Slug is Required',
            ]
        );
        ModelsSection::create($this->section);
        $this->emit('saved');
        $this->updateSections();
        $this->clearAddUpdate();
    }

    public function showUpdate($section_id) {
        $this->updateSection = true;
        $this->section = ModelsSection::find($section_id)->toArray();
    }

    public function update() {
        $this->dispatchBrowserEvent('componentUpdated');
        $this->validate(
            [
                'section.title' => 'required',
                'section.content' => 'required',
                'section.slug' => 'required',
            ],
            [
                'section.title.required' => 'Section Title is Required',
                'section.content.required' => 'Please enter some Content for the Section',
                'section.slug.required' => 'Section Slug is Required',
            ]
        );
        $section = ModelsSection::findOrFail($this->section['id']);
        if (isset($this->section['lang'])) {
            $section = ModelsSection::where('slug', $section->slug)->where('lang', $this->section['lang'])->first();
            if (!$section) {
                $section = new ModelsSection;
            }
        }
        $section->fill($this->section);
        $section->save();
        $this->emit('saved');
    }

    public function translate($locale) {
        $section = ModelsSection::where('slug', $this->section['slug'])->where('lang', $locale)->first();
        if ($section) {
            $this->showUpdate($section->id);
        }
        $this->section['lang'] = $locale;
        $this->section['lang_text'] = config('app.locales_text')[array_search($locale, config('app.locales'))];
        $this->dispatchBrowserEvent('componentUpdated');
    }

    public function isTranslated($locale) {
        if (ModelsSection::where('slug', $this->section['slug'])->where('lang', $locale)->count() > 0) {
            return true;
        }
        return false;
    }

    public function delete($section_id) {
        $section = ModelsSection::findOrFail($section_id);
        $section->delete();
        ModelsSection::where('slug', $section->slug)->delete();
        $this->updateSections();
    }

    public function render() {
        return view('backend.sections.section');
    }
}
