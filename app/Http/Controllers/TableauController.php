<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TableauController extends Controller
{
    public function create(Request $request)
    {
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project = new Project();
        $project->name = $request->input('name');
        $project->description = $request->input('description');
        $project->user_id = Auth::user()->id; // Assuming you have user authentication
        $project->save();

        // Redirect or return a response
        return redirect()->route('dashboard')->with('success', 'Projet crée avec succés!');
    }

    /**
     * Delete a project
     * 
     * @param Project $projet
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Project $projet)
    {
        // Vérification que l'utilisateur est bien le propriétaire du projet
        if ($projet->user_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Vous etes pas autorisé pour supprimer se projet');
        }

        // Suppression des tâches associées au projet (si la relation a été définie avec cascade delete, cette étape est optionnelle)
        $projet->tasks()->delete();
        
        // Suppression du projet
        $projet->delete();

        return redirect()->route('dashboard')->with('success', 'Projet supprimer avec succés!');
    }
}
