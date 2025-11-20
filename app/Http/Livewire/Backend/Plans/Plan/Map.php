<?php

namespace App\Http\Livewire\Backend\Plans\Plan;

use App\Models\Number;
use App\Models\PlanNumberMap;
use Livewire\Component;

class Map extends Component {

    public $maps, $numbers, $plan_id, $number;

    public $types = [];
    public $text = ['text-gray-800', 'text-lime-800', 'text-red-800', 'text-purple-800', 'text-pink-800'];

    public function remove($map_id) {
        PlanNumberMap::findOrFail($map_id)->delete();
        $this->mount();
    }

    public function add() {
        $this->validate(
            [
                'number' => 'required',
            ],
            [
                'number.required' => 'Select a Number to Add'
            ]
        );
        $map = new PlanNumberMap;
        $map->plan_id = $this->plan_id;
        $map->number_id = $this->number;
        $map->save();
        $this->number = null;
        $this->emit('added');
        $this->mount();
    }

    public function mount() {
        $this->types = [
            __('Open'),
            __('Register Only'),
            __('Private'),
            __('Shared Buy'),
            __('Private Buy'),
        ];
        $this->maps = PlanNumberMap::with(['number'])->where('plan_id', $this->plan_id)->orderBy('created_at')->get();
        $mapped_number_ids = $this->maps->pluck('number_id');
        $this->numbers = Number::whereIn('type', [3, 4])->whereNotIn('id', $mapped_number_ids)->get();
    }

    public function render() {
        return view('backend.plans.plan.map');
    }
}
