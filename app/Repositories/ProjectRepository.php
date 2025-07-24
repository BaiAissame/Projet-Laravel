<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProjectRepository
{
    public function getUserProjects(User $user)
    {
        return Project::where(function ($query) use ($user) {
            //is user creator
            $query->where('creator_id', $user->id)
                //or user a member
                ->orWhereHas('members', function ($subQuery) use ($user) {
                    $subQuery->where('user_id', $user->id);
                });
        })
            ->orderBy('created_at', 'desc');
    }
}
