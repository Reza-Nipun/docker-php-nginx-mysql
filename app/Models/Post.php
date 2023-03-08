<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // public function tags(): BelongsToMany
    // {
    //     // post_tag is the pivot table name
    //     // if pivot is created with alphabetical order of the table names, then no need to specify the pivot table name in the belongsToMany method
    //     // Third parameter is the foreign key of the current model
    //     // Fourth parameter is the foreign key of the related model
    //     return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id')->using(PostTag::class)->withPivot('status')->withTimestamps();
    // }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
