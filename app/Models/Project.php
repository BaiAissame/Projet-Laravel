<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    protected $fillable = [
        'name',
        'description',
        'user_id',
        'slug',
    ];

    public function listTasks(): HasMany
    {
        return $this->hasMany(ListTask::class)->orderBy('order');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user')
            ->using(ProjectUser::class)
            ->withTimestamps();
    }

    public function getNomAttribute()
    {
        return $this->name;
    }

    public function getStatutAttribute()
    {
        // Vous pouvez ajouter une logique pour déterminer le statut du projet
        return 'En cours';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            $baseSlug = Str::slug($project->name);
            $slug = $baseSlug;
            $counter = 1;
            while (self::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            $project->slug = $slug;
        });

        static::updating(function ($project) {
            if ($project->isDirty('name')) {
                $baseSlug = Str::slug($project->name);
                $slug = $baseSlug;
                $counter = 1;
                while (self::where('slug', $slug)->where('id', '!=', $project->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                $project->slug = $slug;
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
