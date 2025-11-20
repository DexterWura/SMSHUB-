<?php

namespace App\Http\Livewire\Frontend;

use App\Models\Number;
use App\Models\Order as ModelsOrder;
use App\Models\Page;
use App\Models\Plan;
use App\Models\PlanNumberMap;
use App\Models\User;
use App\Services\Util;
use App\Models\WalletLog;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Order extends Component {

    public $page, $numbers, $plans, $selected, $error, $success, $filters;

    public $types = [];
    public $bg = ['bg-gray-200', 'bg-lime-200', 'bg-red-200', 'bg-purple-200', 'bg-pink-200'];
    public $text = ['text-gray-800', 'text-lime-800', 'text-red-800', 'text-purple-800', 'text-pink-800'];

    public function setNumber($number_id) {
        $this->selected['number'] = Number::whereIn('type', [3, 4])->where('status', 1)->where('id', $number_id)->first();
        $maps = PlanNumberMap::with(['plan' => function ($q) {
            $q->where('is_active', true);
        }])->where('number_id', $number_id)->orderBy('created_at')->get();
        $this->plans = [];
        foreach ($maps as $map) {
            if ($map->plan) {
                array_push($this->plans, $map->plan);
            }
        }
    }

    public function setPlan($plan_id) {
        $this->selected['plan'] = Plan::where('is_active', true)->where('id', $plan_id)->first();
    }

    public function backTo($step) {
        if ($step == 'plans') {
            $this->selected['plan'] = null;
        } else if ($step == 'numbers') {
            $this->selected['number'] = null;
        }
    }

    public function submit() {
        if (Auth::user()->wallet < $this->selected['plan']['price']) {
            $this->error = __('You do not have sufficient balance. Please add some funds before submitting order.');
            return;
        }
        if ($this->checkPrivateOrderExist($this->selected['number']['id'])) {
            $this->error = __('Looks like someone has already purchased it. Please choose another number or try again later.');
            return;
        }
        if ($this->error == null) {
            try {
                DB::beginTransaction();
                $user = User::findOrFail(Auth::id());
                $beforeWallet = $user->wallet;
                $user->wallet = $user->wallet - $this->selected['plan']['price'];
                $user->save();
                $order = new ModelsOrder;
                $order->user_id = Auth::id();
                $order->plan_id = $this->selected['plan']['id'];
                $order->number_id = $this->selected['number']['id'];
                if ($this->selected['plan']['validity_type'] == 'minute') {
                    $order->expiry = Carbon::now()->addMinutes($this->selected['plan']['validity']);
                } else if ($this->selected['plan']['validity_type'] == 'hour') {
                    $order->expiry = Carbon::now()->addHours($this->selected['plan']['validity']);
                } else if ($this->selected['plan']['validity_type'] == 'day') {
                    $order->expiry = Carbon::now()->addDays($this->selected['plan']['validity']);
                } else if ($this->selected['plan']['validity_type'] == 'week') {
                    $order->expiry = Carbon::now()->addWeeks($this->selected['plan']['validity']);
                } else if ($this->selected['plan']['validity_type'] == 'month') {
                    $order->expiry = Carbon::now()->addMonths($this->selected['plan']['validity']);
                } else if ($this->selected['plan']['validity_type'] == 'year') {
                    $order->expiry = Carbon::now()->addYears($this->selected['plan']['validity']);
                }
                $order->auto_renew = false;
                $order->save();
                $log = new WalletLog;
                $log->user_id = $user->id;
                $log->before = $beforeWallet;
                $log->after = $user->wallet;
                $log->log = 'Deducated against Order ID:' . $order->id;
                $log->save();
                $this->success = 'Your order is now placed successfully. You will be redirect to newly purchased number in 5 seconds';
                $this->dispatchBrowserEvent('redirect', ['number' => $this->selected['number']['number']]);
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                $this->error = $e->getMessage();
                return;
            }
        }
    }

    public function dismissError() {
        $this->error = null;
    }

    public function mount() {
        $this->types = [
            __('Open'),
            __('Register Only'),
            __('Private'),
            __('Shared Buy'),
            __('Private Buy'),
        ];
        $this->page = Page::where('slug', 'order')->first();
        $this->page->header = Util::setupHeader($this->page->header, $this->page->meta);
        $this->selected = [
            'number' => null,
            'plan' => null
        ];
        $this->numbers = Number::whereIn('type', [3, 4])->where('status', 1)->get();
        foreach ($this->numbers as $key => $number) {
            if ($number->type == 4 && $this->checkPrivateOrderExist($number->id)) {
                $this->numbers->forget($key);
            }
        }
        $this->filters = [
            'number' => [
                'country' => $this->numbers->pluck('country')
            ]
        ];
    }

    public function render() {
        return view('themes.' . config('app.settings.theme') . '.components.order');
    }

    public function checkPrivateOrderExist($number_id) {
        return (ModelsOrder::where('expiry', '>=', Carbon::now())->where('number_id', $number_id)->count()) ? true : false;
    }

    public function getCountryName($code) {
        return Util::getCountryNameFromCode($code);
    }
}
