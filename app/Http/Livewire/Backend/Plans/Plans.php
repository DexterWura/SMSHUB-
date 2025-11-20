<?php

namespace App\Http\Livewire\Backend\Plans;

use App\Models\Plan;
use Livewire\Component;

class Plans extends Component {

    public $plans, $plan;

    public function mount() {
        $this->plans = Plan::all();
    }

    public function delete($plan_id) {
        Plan::findOrFail($plan_id)->delete();
        $this->mount();
    }

    public function enableDisable($plan_id) {
        $plan = Plan::findOrFail($plan_id);
        $plan->is_active = !$plan->is_active;
        $plan->save();
        $this->mount();
    }

    public function add() {
        $this->plan = [
            'id' => null,
            'name' => '',
            'price' => null,
            'validity' => null,
            'validity_type' => 'day',
        ];
    }

    public function update($plan_id) {
        $this->plan = Plan::find($plan_id)->toArray();
    }

    public function handle() {
        $this->validate(
            [
                'plan.name' => 'required',
                'plan.price' => 'required',
                'plan.validity' => 'required',
                'plan.validity_type' => 'required',
            ],
            [
                'plan.name.required' => 'Plan Name is Required',
                'plan.price.required' => 'Plan Price is Required',
                'plan.validity.required' => 'Plan Validity is Required',
                'plan.validity_type.required' => 'Plan Validity Type is Required',
            ]
        );
        if ($this->plan['id']) {
            $plan = Plan::find($this->plan['id']);
        } else {
            $plan = new Plan;
        }
        $plan->name = $this->plan['name'];
        $plan->price = $this->plan['price'];
        $plan->validity = $this->plan['validity'];
        $plan->validity_type = $this->plan['validity_type'];
        $plan->save();
        $this->plan = null;
        $this->mount();
    }

    public function render() {
        return view('backend.plans.plans');
    }
}
