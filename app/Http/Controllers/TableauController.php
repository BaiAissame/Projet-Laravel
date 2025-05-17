<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TableauController extends Controller
{
    public function create(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project = new Project();
        $project->name = $request->input('name');
        $project->description = $request->input('description');
        $project->user_id = Auth::user()->id; // Assuming you have user authentication
        $project->save();

        // Redirect or return a response
        return redirect()->route('dashboard')->with('success', 'Project created successfully!');
    }

    public function destroy(Project $projet)
    {
        // Vérification que l'utilisateur est bien le propriétaire du projet
        if ($projet->user_id !== Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'You are not authorized to delete this project');
        }

        // Suppression des tâches associées au projet (si la relation a été définie avec cascade delete, cette étape est optionnelle)
        $projet->tasks()->delete();
        
        // Suppression du projet
        $projet->delete();

        return redirect()->route('dashboard')->with('success', 'Project deleted successfully!');
    }
}
