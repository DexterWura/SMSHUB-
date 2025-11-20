<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Assign;
use App\Models\Feature;
use App\Models\Number;
use App\Models\Order;
use App\Models\Section;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Home extends Component {

    public $numbers, $features, $sections, $assigned, $user, $orders, $filters, $private, $rented;

    public $types = [];
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
        $this->orders = collect([]);
        $this->features = Feature::where('lang', null)->get();
        $this->sections = Section::where('lang', null)->get();
        $this->user = Auth::user();
        $this->numbers = Number::public();
        if ($this->user) {
            $this->assigned = Assign::numbers(Auth::id());
            Order::renewExpired($this->user->id);
            $this->orders = Order::with(['number'])->where('user_id', $this->user->id)->where('expiry', '>', Carbon::now())->get();
        } else {
            $this->assigned = collect([]);
            $this->orders = collect([]);
        }
        $this->setupFilters();
    }

    private function setupFilters() {
        $this->private = $this->assigned->pluck('number');
        $this->rented = $this->orders->pluck('number');
        $this->filters = [
            'number' => [
                'country' => array_unique($this->numbers->pluck('country')->toArray())
            ],
            'private' => [
                'country' => array_unique($this->private->pluck('country')->toArray())
            ],
            'rented' => [
                'country' => array_unique($this->rented->pluck('country')->toArray())
            ]
        ];
    }

    public function render() {
        return view('themes.' . config('app.settings.theme') . '.components.home');
    }

    public function diffForHumans($date) {
        $carbon = Carbon::parse($date);
        return $carbon->diffForHumans(null, true);
    }
}
