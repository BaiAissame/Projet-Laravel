<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectMemberController extends Controller
{
    public function index(Project $projet)
    {
        // Vérifier les permissions
        if ($projet->user_id !== Auth::id() && !$projet->members()->where('user_id', Auth::id())->exists()) {
            abort(403);
        }

        $creator = $projet->user;
        $members = $projet->members()->withPivot('created_at')->get();
        $isCreator = $projet->user_id === Auth::id();

        // Récupérer les invitations en attente
        $pendingInvitations = $projet->userInvitations()
            ->where('status', 'pending')
            ->get();

        // AJOUTEZ CETTE LIGNE : Récupérer les projets de l'utilisateur pour la navigation
        $userProjects = Auth::user()->ownedProjects()
            ->select('id', 'name', 'slug')
            ->get();

        return view('projet.members.index', compact('projet', 'creator', 'members', 'isCreator', 'pendingInvitations', 'userProjects'));
    }
    public function removeMember(Project $projet, User $user)
    {
        // Vérifier que l'utilisateur est le créateur
        if ($projet->user_id !== Auth::id()) {
            return back()->with('error', 'Seul le créateur peut retirer des membres.');
        }

        // Empêcher le créateur de se retirer lui-même
        if ($user->id === $projet->user_id) {
            return back()->with('error', 'Le créateur ne peut pas se retirer du projet.');
        }

        // Retirer le membre
        $projet->members()->detach($user->id);

        return back()->with('success', 'Membre retiré avec succès.');
    }
}
