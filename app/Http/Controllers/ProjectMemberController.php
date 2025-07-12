<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectMemberController extends Controller
{
    /**
     * Afficher la page de gestion des membres du projet
     */
    public function index(Project $projet)
    {
        // Vérifier que l'utilisateur est membre du projet ou créateur
        if (!$projet->members->contains(Auth::id()) && $projet->user_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas accès à ce projet.');
        }

        // Récupérer tous les membres du projet (créateur + membres)
        $members = $projet->members;
        $creator = $projet->user;
        
        // Vérifier si l'utilisateur actuel est le créateur du projet
        $isCreator = $projet->user_id === Auth::id();

        // Récupérer tous les projets de l'utilisateur pour la navigation
        $userProjects = Project::where('user_id', Auth::id())->get();

        return view('projet.members.index', compact('projet', 'members', 'creator', 'isCreator', 'userProjects'));
    }

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
            return redirect()->back()->with('error', 'Cet utilisateur n\'est pas inscrit sur la plateforme.');
        }
        
        // Vérifier que l'utilisateur n'est pas déjà membre du projet
        // et qu'il n'est pas le createur du projet
        if ($projet->members->contains($user->id) || $projet->user_id === $user->id) {
            return redirect()->back()->with('error', 'Cet utilisateur est déjà membre du projet.');
        }
        
        // Ajouter l'utilisateur comme membre du projet
        try {
            $projet->members()->attach($user->id);
            return redirect()->back()->with('success', $user->name . ' a été ajouté avec succès au projet.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'ajout du membre.');
        }
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