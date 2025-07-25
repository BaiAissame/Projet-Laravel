<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskColumn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KanbanController extends Controller
{
    public function index(Request $request)
    {
        $projetId = $request->get('projet');

        if ($projetId) {
            $projet = Project::where('id', $projetId)
                ->where(function ($query) {
                    $query->where('user_id', Auth::id())
                        ->orWhereHas('members', function ($q) {
                            $q->where('user_id', Auth::id());
                        });
                })
                ->firstOrFail();
        } else {
            $projet = Project::where('user_id', Auth::id())
                ->orWhereHas('members', function ($query) {
                    $query->where('user_id', Auth::id());
                })
                ->first();
        }

        if (!$projet) {
            return redirect()->route('dashboard')->with('error', 'Aucun projet trouvé.');
        }

        $colonnes = TaskColumn::with([
            'tasks' => function ($query) use ($projet) {
                $query->whereHas('listTask', function ($q) use ($projet) {
                    $q->where('project_id', $projet->id);
                })
                    ->with(['assignes', 'listTask'])
                    ->orderBy('order');
            }
        ])->get();

        $tasks = Task::whereHas('listTask', function ($query) use ($projet) {
            $query->where('project_id', $projet->id);
        })
            ->with(['assignes', 'colonne', 'listTask'])
            ->orderBy('order')
            ->get();

        $projets = Project::where('user_id', Auth::id())
            ->orWhereHas('members', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->select('id', 'name', 'slug')
            ->get();

        return view('kanban.index', compact('projet', 'colonnes', 'tasks', 'projets'));
    }
}