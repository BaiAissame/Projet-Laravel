<?php

namespace App\Services\Project;

use App\Models\UserInvitation;
use App\Models\Project;
use App\Models\User;
use App\Repositories\InvitationRepository;
use Illuminate\Support\Facades\Auth;

class InvitationService
{

    protected $invitationRepository;


    public function __construct(InvitationRepository $invitationRepository)
    {
        $this->invitationRepository = $invitationRepository;
    }


    public function invite(Project $project, string $email): array
    {
        $currentUser = Auth::user();

        $user = User::where('email', $email)->first();

        if ($user) {
            if ($this->invitationRepository->isUserProjectMember($project, $user)) {
                return [
                    'success' => false,
                    'message' => 'Utilisateur est déjà membre du projet.'
                ];
            }
        }

        $existingInvitation = $this->invitationRepository->findExistingInvitation($project->id, $email);

        if ($existingInvitation) {
            $existingInvitation->update([
                'status' => 'pending',
                'inviter_id' => $currentUser->id,
            ]);


            return [
                'success' => true,
                'message' => 'Invitation a été renvoyé.',
                'invitation' => $existingInvitation
            ];
        }

        $invitation = UserInvitation::create([
            'project_id' => $project->id,
            'email' => $email,
            'status' => 'pending',
            'inviter_id' => $currentUser->id,
        ]);


        return [
            'success' => true,
            'message' => 'Invitation envoyé.',
            'invitation' => $invitation
        ];
    }

    public function finalizeAcceptance(int $invitationId): array
    {
        $invitation = $this->invitationRepository->findWithRelations($invitationId);


        if (!$invitation || $invitation->status !== 'pending') {
            return [
                'success' => false,
                'message' => 'Invitation invalide ou expiré.',
                'requires_verification' => false
            ];
        }

        $user = Auth::user();

        if ($user->email !== $invitation->email) {
            return [
                'success' => false,
                'message' => 'Invitation ne vous concerne pas.',
                'requires_verification' => false
            ];
        }

        $project = $invitation->project;

        if (!$this->invitationRepository->isUserProjectMember($project, $user)) {
            $project->members()->attach($user->id, ['role' => 'member']);
        }

        $invitation->update(['status' => 'accepted']);

        return [
            'success' => true,
            'message' => 'Vous avez accepté de rejoindre le projet !',
            'project_id' => $project->id,
            'requires_verification' => false
        ];
    }


    public function declineInvitation(int $invitationId): array
    {
        $invitation = $this->invitationRepository->findWithRelations($invitationId);

        if (!$invitation) {
            return [
                'success' => false,
                'message' => 'Invitation non trouvée.'
            ];
        }

        $user = Auth::user();
        if ($user->email !== $invitation->email) {
            return [
                'success' => false,
                'message' => 'Invitation ne concerne pas votre email.'
            ];
        }

        $invitation->update(['status' => 'declined']);

        return [
            'success' => true,
            'message' => 'Invitation rejeté.'
        ];
    }
}