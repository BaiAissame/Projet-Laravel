<?php

namespace App\Repositories;

use App\Models\UserInvitation;
use App\Models\User;
use App\Models\Project;

class InvitationRepository
{

    public function findExistingInvitation(int $projectId, string $email): ?UserInvitation
    {
        return UserInvitation::where('project_id', $projectId)
            ->where('email', $email)
            ->first();
    }
    

    public function findWithRelations(int $invitationId): ?UserInvitation
    {
        return UserInvitation::with(['project', 'inviter'])->find($invitationId);
    }

    public function isUserProjectMember(Project $project, User $user): bool
    {
        return $project->members()->where('user_id', $user->id)->exists() 
            || $project->creator_id === $user->id;
    }
    

    public function isEmailProjectMember(Project $project, string $email): bool
    {
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return false;
        }
        
        return $this->isUserProjectMember($project, $user);
    }
} 