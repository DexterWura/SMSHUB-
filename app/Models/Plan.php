<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model {
    use HasFactory;

    public function numbers() {
        $number_ids = PlanNumberMap::where('plan_id', $this->id)->pluck('number_id');
        return Number::whereIn('id', $number_ids)->get();
    }
}
