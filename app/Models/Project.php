<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    protected $guarded = [];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function tasks(): HasManyThrough
    {
        return $this->hasManyThrough(Task::class, Team::class, 'project_id', 'user_id', 'id', 'user_id');
    }

    public function task(): HasOneThrough
    {
        return $this->hasOneThrough(Task::class, Team::class, 'project_id', 'user_id', 'id', 'user_id');
    }
}
