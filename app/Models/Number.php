<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Number extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'number',
        'country',
        'meta',
        'type',
        'status'
    ];

    public function plans() {
        $plan_ids = PlanNumberMap::where('number_id', $this->id)->pluck('plan_id');
        return Plan::whereIn('id', $plan_ids)->get();
    }

    public function hasUserOrdered($user_id) {
        return Order::where('user_id', $user_id)->where('number_id', $this->id)->where('expiry', '>', Carbon::now())->count() ? true : false;
    }

    public static function public() {
        return Number::whereIn('type', [0, 1])->where('status', 1)->get();
    }
}
