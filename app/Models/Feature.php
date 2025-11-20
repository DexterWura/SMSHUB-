<?php

namespace App\Models;

use App\Services\Util;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'icon',
        'lang',
        'translate_parent_id',
    ];

    public function getTitleAttribute($value) {
        return Util::translateModelAttribute($this, $this->getTable(), 'title', $value);
    }

    public function getDescriptionAttribute($value) {
        return Util::translateModelAttribute($this, $this->getTable(), 'description', $value);
    }
}
