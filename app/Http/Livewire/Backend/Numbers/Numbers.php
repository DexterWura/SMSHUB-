<?php

namespace App\Http\Livewire\Backend\Numbers;

use App\Models\Assign;
use App\Models\Message;
use App\Models\Number;
use App\Models\User;
use App\Services\Util;
use Livewire\Component;

class Numbers extends Component {

    public $error, $search = '', $order = 'asc', $orderby = 'id', $numbers = [], $number, $numberToAssign, $assignees = [], $showManageAssignees = false, $users = [], $user = null;
    public $types = [];
    public $filters = [
        'type' => null
    ];
    public $bg = ['bg-gray-200', 'bg-lime-200', 'bg-red-200', 'bg-purple-200', 'bg-pink-200'];
    public $text = ['text-gray-800', 'text-lime-800', 'text-red-800', 'text-purple-800', 'text-pink-800'];

    public function mount() {
        $this->types = [
            __('Open'),
            __('Register Only'),
            __('Private'),
            __('Shared Buy'),
            __('Private Buy'),
        ];
        $this->users = User::get();
    }

    public function set($number_id) {
        $this->error = null;
        $this->number = Number::find($number_id)->toArray();
    }

    public function add() {
        $this->number = [
            'id' => null,
            'number' => '',
            'country' => null,
            'meta' => null,
            'type' => null,
            'status' => 1
        ];
    }

    public function handle() {
        $this->error = null;
        $this->validate(
            [
                'number.number' => 'required',
                'number.country' => 'required',
                'number.type' => 'required',
            ],
            [
                'number.number.required' => 'Number is Required',
                'number.country.required' => 'Please select a Country to proceed',
                'number.type.required' => 'Please select a Type to proceed',
            ]
        );
        if ($this->number['id']) {
            $number = Number::find($this->number['id']);
            if ($number->type == 2 && $this->number['type'] != 2 && Assign::where('number_id', $this->number['id'])->count() > 0) {
                $this->error = 'Number is assigned to user(s). Please delete the assignment to change the number type.';
                return;
            }
        } else {
            $number = new Number;
        }
        $number->number = $this->number['number'];
        $number->country = $this->number['country'];
        $number->meta = $this->number['meta'];
        $number->type = $this->number['type'];
        $number->status = $this->number['status'];
        $number->save();
        $this->number = null;
    }

    public function manageAssignees($number_id) {
        $this->showManageAssignees = true;
        $this->assignees = Assign::with('user')->where('number_id', $number_id)->get();
        $this->numberToAssign = Number::findOrFail($number_id);
    }

    public function removeAssignee($assign_id) {
        Assign::findOrFail($assign_id)->delete();
        $this->manageAssignees($this->numberToAssign->id);
    }

    public function assign() {
        $number_id = $this->numberToAssign->id;
        if (Assign::where('user_id', $this->user)->where('number_id', $number_id)->count() == 0) {
            $assign = new Assign;
            $assign->user_id = $this->user;
            $assign->number_id = $number_id;
            $assign->save();
            $this->manageAssignees($number_id);
        }
        $this->user = null;
    }

    public function toggleStatus($number_id) {
        $number = Number::find($number_id);
        $number->status = $number->status == 0 ? 1 : 0;
        $number->save();
        $this->mount();
    }

    public function truncate($number_id) {
        $number = Number::find($number_id);
        Message::where('to', $number->number)->delete();
        $this->mount();
    }

    public function delete($number_id) {
        $number = Number::find($number_id);
        $number->delete();
        $this->mount();
    }

    public function getCountryName($code) {
        return Util::getCountryNameFromCode($code);
    }

    public function getCountries() {
        return Util::$country;
    }

    public function changeOrder() {
        if ($this->order == 'asc') {
            $this->order = 'desc';
        } else {
            $this->order = 'asc';
        }
    }

    public function render() {
        $query = Number::query();
        if ($this->search) {
            $query = $query->where('number', 'like', '%' . $this->search . '%');
        }
        if ($this->filters['type'] != null && $this->filters['type'] != 'null') {
            $query = $query->where('type', $this->filters['type']);
        }
        $this->numbers = $query->orderBy($this->orderby, $this->order)->get();
        return view('backend.numbers.numbers');
    }
}
