<?php

namespace App\Http\Livewire\Backend\Numbers\Number;

use App\Models\Plan;
use App\Models\PlanNumberMap;
use Livewire\Component;

class Map extends Component {

    public $maps, $plans, $number_id, $plan;

    public function remove($map_id) {
        PlanNumberMap::findOrFail($map_id)->delete();
        $this->mount();
    }

    public function add() {
        $this->validate(
            [
                'plan' => 'required',
            ],
            [
                'plan.required' => 'Select a Plan to Add'
            ]
        );
        $map = new PlanNumberMap;
        $map->number_id = $this->number_id;
        $map->plan_id = $this->plan;
        $map->save();
        $this->plan = null;
        $this->emit('added');
        $this->mount();
    }

    public function mount() {
        $this->maps = PlanNumberMap::with(['plan'])->where('number_id', $this->number_id)->orderBy('created_at')->get();
        $mapped_plan_ids = $this->maps->pluck('plan_id');
        $this->plans = Plan::whereNotIn('id', $mapped_plan_ids)->get();
    }

    public function render() {
        return view('backend.numbers.number.map');
    }
}
