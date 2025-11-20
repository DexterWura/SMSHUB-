<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assign extends Model {
    use HasFactory;

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function number() {
        return $this->belongsTo('App\Models\Number');
    }

    public static function numbers($user_id) {
        $numbers = Assign::with(['number'])->where('user_id', $user_id)->get();
        return $numbers;
    }
}
