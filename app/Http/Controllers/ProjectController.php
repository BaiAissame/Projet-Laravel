<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\ProjectUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function create(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Vérifiez d'abord si un projet avec ce nom existe déjà pour cet utilisateur
        $exists = Project::where('name', $request->input('name'))
            ->where('user_id', Auth::id())
            ->exists();

        if ($exists) {
            // Redirigez avec un message d'erreur
            return redirect()->back()
                ->withInput()
                ->with('error', 'Vous avez déjà un projet avec ce nom. Veuillez choisir un nom différent.');
        }

        // Validate the request data

            try {
            $projet = Project::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'user_id' => Auth::id(), // Assuming you have user authentication
            ]);

        } catch (\Throwable $th) {
            return redirect()->back()->with(
                'error',
                'Une erreur est survenue lors de la création du projet.'
            );
        }

        // Redirect or return a response
        return redirect()->route('dashboard')->with('success', 'Projet crée avec succés!');
    }


    public function show(Project $projet)
    {
        if ($projet->user_id != Auth::id() && !$projet->members->contains(Auth::id())) {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas accès à ce projet.');
        }

        // Charger les relations nécessaires
        $projet->load([
            'listTasks.tasks.assignes',
            'listTasks.tasks.comments.user',
            'listTasks.tasks.tags',
            'members'
        ]);

        //pour navbar left
        $projets = Project::where('user_id', Auth::user()->id)->get();
        return view('projet.show', compact('projets', 'projet'));
    }


    public function destroy(Project $projet)
    {
        // Vérification que l'utilisateur est bien le propriétaire du projet
        if ($projet->user_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Vous etes pas autorisé pour supprimer se projet');
        }

        // Suppression des tâches associées au projet (si la relation a été définie avec cascade delete, cette étape est optionnelle)
        // if($projet->listTasks()) $projet->tasks()->delete();

        // Suppression du projet
        $projet->delete();

        return redirect()->route('dashboard')->with('success', 'Projet supprimer avec succés!');
    }
}
