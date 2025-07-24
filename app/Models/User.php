<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use Laratrust\Traits\HasRolesAndPermissions;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Projets partagés avec cet utilisateur (relation inverse de Project::members)
    public function sharedProjects()
    {
        return $this->belongsToMany(Project::class, 'project_user')
                    ->using(ProjectUser::class)
                    ->withTimestamps();
    }

    // Optionnel : projets créés par cet utilisateur
    public function ownedProjects()
    {
        return $this->hasMany(Project::class);
    }
}