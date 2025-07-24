<?php

namespace App\Services\Project;

use App\Models\UserInvitation;
use App\Models\Project;
use App\Models\User;
use App\Repositories\InvitationRepository;
use App\Mail\ProjectInvitation as ProjectInvitationMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
        
        //check if user is already project member
        $user = User::where('email', $email)->first();
        
        if ($user) {
            if ($this->invitationRepository->isUserProjectMember($project, $user)) {
                return [
                    'success' => false,
                    'message' => 'Utilisateur est déjà membre du projet.'
                ];
            }
        }
        
        //check if any invitation already exists for this project and email (ignore status)
        $existingInvitation = $this->invitationRepository->findExistingInvitation($project->id, $email);
            
        if ($existingInvitation) {
            //if there is already an invitation, update status to pending
            $existingInvitation->update([
                'status' => 'pending',
                'inviter_id' => $currentUser->id,
            ]);
            
            Mail::to($email)->send(new ProjectInvitationMail($project, $existingInvitation, $user !== null));
            
            return [
                'success' => true,
                'message' => 'Invitation a été renvoyé.'
            ];
        }
        
        $invitation = UserInvitation::create([
            'project_id' => $project->id,
            'email' => $email,
            'status' => 'pending',
            'inviter_id' => $currentUser->id,
        ]);
        
        Mail::to($email)->send(new ProjectInvitationMail($project, $invitation, $user !== null));
        
        return [
            'success' => true,
            'message' => 'Invitation envoyé.'
        ];
    }

    public function finalizeAcceptance(int $invitationId): array
    {
        $invitation = $this->invitationRepository->findWithRelations($invitationId);
        
        //validate
        if (!$invitation || $invitation->status !== 'pending') {
            return [
                'success' => false,
                'message' => 'Invitation invalide ou expiré.',
                'requires_verification' => false
            ];
        }
        
        $user = Auth::user();
        
        //check if invitation email matches the logged in user's email
        if ($user->email !== $invitation->email) {
            return [
                'success' => false,
                'message' => 'Invitation ne vous concerne pas.',
                'requires_verification' => false
            ];
        }

        // //check if email is verified
        // if (!$user->hasVerifiedEmail()) {
        //     return [
        //         'success' => false,
        //         'message' => 'Vous devez verifier votre email avant de répondre a une invitation.',
        //         'requires_verification' => true,
        //         'invitation_id' => $invitation->id
        //     ];
        // }
        
        $project = $invitation->project;
        
        //add user to project if not already member
        if (!$this->invitationRepository->isUserProjectMember($project, $user)) {
            $project->members()->attach($user->id, ['role' => 'member']);
        }
        
        //update status
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
        
        //check if invitation is for the current user
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