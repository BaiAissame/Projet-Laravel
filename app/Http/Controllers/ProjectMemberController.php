<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectMemberController extends Controller
{
    /**
     * Ajout un membre a un projet
     */
    public function addMember(Request $request, Project $projet)
    {
        // Verifier que l'utilisateur courant est le createur du projet
        if ($projet->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à ajouter des membres à ce projet.');
        }
        
        // Valider le formulaire
        $validated = $request->validate([
            'email' => 'required|email',
        ]);
        
        // Chercher l'utilisateur par email
        $user = User::where('email', $validated['email'])->first();
        
        // Si l'utilisateur n'existe pas
        if (!$user) {
            return redirect()->back()->with('error', 'Cet utilisateur n\'est pas inscrit.');
        }
        
        // Vérifier que l'utilisateur n'est pas déjà membre du projet
        // et qu'il n'est pas le createur du projet
        if ($projet->members->contains($user->id) || $projet->user_id === $user->id) {
            return redirect()->back()->with('error', 'Cet utilisateur est déjà membre du projet.');
        }
        
        // Ajouter l'utilisateur comme membre du projet
        $projet->members()->attach($user->id);
        
        return redirect()->back()->with('success', 'Membre ajouté avec succès au projet.');
    }
    
    /**
     * Retire un membre d'un projet
     */
    public function removeMember(Project $projet, User $user)
    {
        // verfier que l'utilisateur courant est le createur du projet
        if ($projet->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à retirer des membres de ce projet.');
        }
        
        // Retirer l'utilisateur des membres du projet
        $projet->members()->detach($user->id);
        
        return redirect()->back()->with('success', 'Membre retiré du projet avec succès.');
    }
}