<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // public function posts(): BelongsToMany
    // {
    //     // post_tag is the pivot table name
    //     // if pivot is created with alphabetical order of the table names, then no need to specify the pivot table name in the belongsToMany method
    //     // Third parameter is the foreign key of the current model
    //     // Fourth parameter is the foreign key of the related model
    //     return $this->belongsToMany(Post::class, 'post_tag', 'tag_id', 'post_id')->withPivot('status')->withTimestamps();
    // }

    public function posts(): BelongsToMany
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }

    public function videos(): BelongsToMany
    {
        return $this->morphedByMany(Video::class, 'taggable');
    }
}
