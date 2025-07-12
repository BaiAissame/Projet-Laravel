<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'order',
        'list_task_id',
        'user_id',
        'category',
        'priority',
        'created_at',
        'updated_at',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_user')
            ->using(TaskUser::class)
            ->withTimestamps();
    }

    public function assignes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'task_user')
            ->using(TaskUser::class)
            ->withTimestamps();
    }

    public function taskCategory()
    {
        return $this->belongsTo(TaskCategory::class, 'task_category_id');
    }

    public function taskPriority()
    {
        return $this->belongsTo(TaskPriority::class, 'task_priority_id');
    }

    public function listTask(): BelongsTo
    {
        return $this->belongsTo(ListTask::class);
    }

    public function taskUsers(): HasMany
    {
        return $this->hasMany(TaskUser::class, 'task_id');
    }

    public function colonne(): BelongsTo
    {
        return $this->belongsTo(TaskColumn::class, 'colonne_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(TaskTag::class);
    }
}
