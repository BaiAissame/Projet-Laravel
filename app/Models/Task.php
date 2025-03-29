<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'project',
        'task_category_id',
        'task_priority_id',
        'created_at',
        'updated_at',
    ];

    public function users(): BelongsToMany
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
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
