<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskViewController extends Controller
{
    public function listView(Request $request, Project $projet)
    {
        $query = Task::with(['users', 'listTask'])
            ->whereHas('listTask', function ($q) use ($projet) {
                $q->where('project_id', $projet->id);
            });

        // Filtrage par recherche
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Filtrage par catégorie
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // Filtrage par priorité
        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        // Filtrage par utilisateur assigné
        if ($request->filled('assigned_user')) {
            $query->whereHas('users', function ($q) use ($request) {
                $q->where('user_id', $request->input('assigned_user'));
            });
        }

        // Tri
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $tasks = $query->paginate(20);

        // Obtenir les utilisateurs du projet pour les filtres
        // CORRECTION : Utiliser members() au lieu de users()
        $projectUsers = $projet->members()->get();

        return view('tasks.list', compact('tasks', 'projet', 'projectUsers'));
    }

    public function calendarView(Request $request, Project $projet)
    {
        $view = $request->input('view', 'month'); // day, threeDays, week, month
        $date = $request->input('date', now()->format('Y-m-d'));
        $currentDate = Carbon::parse($date);

        // Définir les périodes selon la vue
        switch ($view) {
            case 'day':
                $startDate = $currentDate->copy()->startOfDay();
                $endDate = $currentDate->copy()->endOfDay();
                break;
            case 'threeDays':
                $startDate = $currentDate->copy()->startOfDay();
                $endDate = $currentDate->copy()->addDays(2)->endOfDay();
                break;
            case 'week':
                $startDate = $currentDate->copy()->startOfWeek();
                $endDate = $currentDate->copy()->endOfWeek();
                break;
            case 'month':
            default:
                $startDate = $currentDate->copy()->startOfMonth()->startOfWeek();
                $endDate = $currentDate->copy()->endOfMonth()->endOfWeek();
                break;
        }

        // Récupérer les tâches avec date limite dans la période
        $tasks = Task::with(['users', 'listTask'])
            ->whereHas('listTask', function ($q) use ($projet) {
                $q->where('project_id', $projet->id);
            })
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [$startDate, $endDate])
            ->get();

        // Organiser les tâches par date pour le calendrier
        $tasksByDate = $tasks->groupBy(function ($task) {
            return Carbon::parse($task->due_date)->format('Y-m-d');
        });

        return view('tasks.calendar', compact('tasks', 'tasksByDate', 'projet', 'view', 'currentDate', 'startDate', 'endDate'));
    }

    public function updateDueDate(Request $request, Task $task)
    {
        $request->validate([
            'due_date' => 'nullable|date'
        ]);

        try {
            $task->update(['due_date' => $request->input('due_date')]);
            return response()->json(['error' => false, 'message' => 'Date limite mise à jour avec succès']);
        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'message' => $th->getMessage()], 500);
        }
    }

    public function details(Task $task)
    {
        $task->load(['users', 'listTask']);
        
        return response()->json([
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'category' => $task->category,
            'priority' => $task->priority,
            'due_date' => $task->due_date ? $task->due_date->format('Y-m-d') : null,
            'users' => $task->users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                ];
            }),
            'list_task' => [
                'id' => $task->listTask->id,
                'title' => $task->listTask->title,
            ],
        ]);
    }
}