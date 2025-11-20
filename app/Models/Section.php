<?php

namespace App\Models;

use App\Services\Util;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model {
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
        'slug',
        'lang',
    ];

    public function getTitleAttribute($value) {
        return Util::translateModelAttribute($this, $this->getTable(), 'title', $value);
    }

    public function getContentAttribute($value) {
        return Util::translateModelAttribute($this, $this->getTable(), 'content', $value);
    }
}
