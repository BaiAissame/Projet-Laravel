<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\ProjectUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\ProjectsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class ProjectController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $exists = Project::where('name', $request->input('name'))
            ->where('user_id', Auth::id())
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Vous avez déjà un projet avec ce nom. Veuillez choisir un nom différent.');
        }

        try {
            $projet = Project::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'user_id' => Auth::id(),
            ]);

        } catch (\Throwable $th) {
            return redirect()->back()->with(
                'error',
                'Une erreur est survenue lors de la création du projet.'
            );
        }

        return redirect()->route('dashboard')->with('success', 'Projet crée avec succés!');
    }

    public function show(Project $projet)
    {
        if ($projet->user_id != Auth::id() && !$projet->members->contains(Auth::id())) {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas accès à ce projet.');
        }

        $projet->load([
            'listTasks' => function ($query) {
                $query->orderBy('order');
            },
            'listTasks.tasks' => function ($query) {
                $query->orderBy('order');
            },
            'listTasks.tasks.assignes',
            'listTasks.tasks.comments' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'listTasks.tasks.comments.user',
            'listTasks.tasks.tags',
            'members'
        ]);

        $projets = Project::where('user_id', Auth::user()->id)
            ->select('id', 'name', 'slug')
            ->get();

        return view('projet.show', compact('projets', 'projet'));
    }

    public function destroy(Project $projet)
    {
        if (!Auth::user() || !Auth::user()->hasRole('admin')) {
            return redirect()->route('dashboard')->with('error', 'Seuls les administrateurs peuvent supprimer un projet.');
        }

        try {
            $projet->delete();
            return redirect()->route('dashboard')->with('success', 'Projet supprimé avec succès!');
        } catch (\Throwable $e) {
            return redirect()->route('dashboard')->with('error', 'Erreur lors de la suppression du projet.');
        }
    }

    public function export()
    {
        try {
            $fileName = 'projects_export_' . Auth::id() . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
            $filePath = 'exports/' . $fileName;

            Excel::queue(new ProjectsExport, $filePath, 'public');

            return redirect()->back()->with('success', 'Export en cours de traitement. Le fichier sera disponible dans quelques instants.');

        } catch (\Throwable $e) {
            Log::error('Erreur lors du lancement de l\'export: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors du lancement de l\'export.');
        }
    }
}