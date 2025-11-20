<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class Order extends Model {
    use HasFactory;

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function number() {
        return $this->belongsTo('App\Models\Number');
    }

    public function plan() {
        return $this->belongsTo('App\Models\Plan');
    }

    public function renew() {
        try {
            DB::beginTransaction();
            $plan = Plan::findOrFail($this->plan_id);
            $user = User::findOrFail($this->user_id);
            $beforeWallet = $user->wallet;
            $user->wallet = $user->wallet - $plan->price;
            $user->save();
            $order = new Order;
            $order->user_id = $user->id;
            $order->plan_id = $plan->id;
            $order->number_id = $this->number_id;
            $expiry = Carbon::now();
            if ($expiry < $this->expiry) {
                $expiry = Carbon::parse($this->expiry);
            }
            if ($plan->validity_type == 'minute') {
                $order->expiry = $expiry->addMinutes($plan->validity);
            } else if ($plan->validity_type == 'hour') {
                $order->expiry = $expiry->addHours($plan->validity);
            } else if ($plan->validity_type == 'day') {
                $order->expiry = $expiry->addDays($plan->validity);
            } else if ($plan->validity_type == 'week') {
                $order->expiry = $expiry->addWeeks($plan->validity);
            } else if ($plan->validity_type == 'month') {
                $order->expiry = $expiry->addMonths($plan->validity);
            } else if ($plan->validity_type == 'year') {
                $order->expiry = $expiry->addYears($plan->validity);
            }
            $order->auto_renew = true;
            $order->save();
            $log = new WalletLog;
            $log->user_id = $user->id;
            $log->before = $beforeWallet;
            $log->after = $user->wallet;
            $log->log = 'Deducated against Order ID:' . $order->id;
            $log->save();
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public static function renewExpired($user_id) {
        $orders = Order::where('user_id', $user_id)->where('auto_renew', true)->where('expiry', '<', Carbon::now())->get();
        foreach ($orders as $order) {
            $order->renew();
            $order->auto_renew = false;
            $order->save();
        }
    }
}
