<?php

namespace App\Http\Controllers\Project;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Models\UserInvitation;
use App\Repositories\InvitationRepository;
use App\Services\Project\InvitationService;
use App\Services\InvitationService as SessionInvitationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\MemberJoinedProjectNotification;
use App\Jobs\SendInvitationEmailJob;

class ProjectInvitationController extends Controller
{

    protected $invitationRepository;

    protected $invitationService;

    public function __construct(InvitationRepository $invitationRepository, InvitationService $invitationService)
    {
        $this->invitationRepository = $invitationRepository;
        $this->invitationService = $invitationService;
    }


    public function invite(Request $request, Project $project)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $result = $this->invitationService->invite($project, $request->input('email'));

        // Envoyer l'email d'invitation via la queue si l'invitation a réussi
        if ($result['success'] && isset($result['invitation'])) {
            // Vérifier si l'utilisateur existe déjà
            $userExists = \App\Models\User::where('email', $request->input('email'))->exists();
            
            SendInvitationEmailJob::dispatch($result['invitation'], $project, $userExists)
                ->onQueue('emails')
                ->delay(now()->addSeconds(5));
        }

        if ($request->expectsJson()) {
            return response()->json($result, $result['success'] ? 200 : 422);
        }

        if ($result['success']) {
            return back()->with('success', $result['message']);
        } else {
            return back()->with('error', $result['message']);
        }
    }

    //accept invitation
    public function accept(Request $request, UserInvitation $invitation)
    {
        //check if valid
        if ($invitation->status !== 'pending') {
            return redirect()->route('dashboard')->with('error', 'This invitation is no longer valid.');
        }

        $user = Auth::user();

        //check if invitation email matches authenticated user email
        if ($user->email !== $invitation->email) {
            return redirect()->route('dashboard')->with('error', 'This invitation is for a different email address.');
        }

        //load project and inviter information
        $invitation->load(['project', 'inviter']);

        return redirect()->route('project.invitation.pending', $invitation->id);
    }


    public function finalizeAcceptance(Request $request)
    {
        $invitationId = $request->input('invitation_id');
        $result = $this->invitationService->finalizeAcceptance($invitationId);

        // Retrieve the invitation to access its project and admin
        $invitation = $this->invitationRepository->findWithRelations($invitationId);

        $user = Auth::user();

        if ($invitation && $invitation->project && $result['success']) {
            $project = $invitation->project;
            $admin = $project->creator; // or $project->owner, depending on your model

            if ($admin && $admin->id !== $user->id) {
                $admin->notify(new \App\Notifications\MemberJoinedProjectNotification($project, $user));
            }
        }

        $statusCode = $result['success'] ? 200 : ($result['requires_verification'] ? 403 : 422);
        return response()->json($result, $statusCode);
    }


    public function declineInvitation(Request $request)
    {
        $invitationId = $request->input('invitation_id');
        $result = $this->invitationService->declineInvitation($invitationId);

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    //register before responding the invitation
    public function handlePostRegistration(Request $request)
    {
        $invitationId = $request->query('invitation');

        if (!$invitationId) {
            return null;
        }

        $invitation = $this->invitationRepository->findWithRelations($invitationId);

        if (!$invitation || $invitation->status !== 'pending') {
            return null;
        }

        $user = Auth::user();

        if ($user->email !== $invitation->email) {
            return null;
        }

        SessionInvitationService::storePendingInvitation($invitation->id);

        return redirect()->route('project.invitation.pending', $invitation->id);
    }


    public function showPendingInvitation(Request $request, $invitationId)
    {
        $invitation = $this->invitationRepository->findWithRelations($invitationId);

        if (!$invitation || $invitation->status !== 'pending') {
            return redirect()->route('dashboard')->with('error', 'Cette invitation n\'est plus valide.');
        }

        $user = Auth::user();

        if ($user->email !== $invitation->email) {
            return redirect()->route('dashboard')->with('error', 'Cette invitation est pour une adresse email différente.');
        }

        return view('projet.pending-invitation', [
            'invitation' => $invitation,
            'project' => $invitation->project,
            'inviter' => $invitation->inviter,
        ]);
    }
}