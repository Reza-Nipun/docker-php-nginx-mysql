<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PostTag extends Pivot
{
    use HasFactory;

    protected $table = 'post_tag';

    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            dd('created event', $model);
        });

        static::deleted(function ($model) {
            dd('delete event', $model);
        });
    }
}
