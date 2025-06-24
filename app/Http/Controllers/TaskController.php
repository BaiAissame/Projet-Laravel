<?php

namespace App\Http\Controllers;

use App\Models\ListTask;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskUser;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function create(Request $request, ListTask $listTask)
    {
        $request->validate([
            'titleTask' => 'required|string|max:255'
        ]);

        $lastOrder = Task::where('list_task_id', $listTask->id)->max('order');
        $newOrder = $lastOrder ? $lastOrder + 1 : 1;

        try {
            $task = Task::create([
                'title' => $request->input('titleTask'),
                'order' => $newOrder,
                'user_id' => Auth::id(),
                'list_task_id' => $listTask->id,
            ]);
            $html = view('components.block-task', compact('task'))->render();
            return response()->json(['error' => false, 'message' => 'Tâche créée avec succès', 'html' => $html]);
        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'message' => $th]);
        }
    }

    public function delete(Task $task)
    {
        try {
            if ($task->user_id !== Auth::id()) return response()->json(['error' => true, 'message' => 'Vous n\'êtes pas autorisé à supprimer cette tâche']);

            $task->delete();
            return response()->json(['error' => false, 'message' => 'Tâche supprimée avec succès']);
        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'message' => $th]);
        }
    }

    public function updateOrder(Request $request)
    {

        $request->validate([
            'orderTask' => 'required|array'
        ]);

        $orderData = $request->input('orderTask');

        foreach ($orderData as $item) {
            Task::where('id', $item['taskId'])
                ->update([
                    'order' => $item['order'],
                    'list_task_id' => $item['listTaskId']
                ]);
        }

        return response()->json([
            'error' => false,
            'message' => "ok",
        ]);
    }

    public function join(Task $task)
    {
        try {
            TaskUser::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'task_id' => $task->id,
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            return response()->json(['error' => false, 'message' => 'Utilisateur ajouté à la tâche avec succès']);
        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'message' => $th->getMessage()]);
        }
    }

    public function leave(Task $task)
    {
        try {
            TaskUser::where('user_id', Auth::id())
                ->where('task_id', $task->id)
                ->delete();
            return response()->json(['error' => false, 'message' => 'Utilisateur retiré de la tâche avec succès']);
        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'message' => $th->getMessage()], 500);
        }
    }

    public function updateCategory(Request $request, Task $task)
    {
        $request->validate([
            'category' => 'required|string|in:marketing,développement,communication'
        ]);

        try {
            $task->update(['category' => $request->input('category')]);
            return response()->json(['error' => false, 'message' => 'Catégorie mise à jour avec succès', 'value' => $task]);
        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'message' => $th->getMessage()], 500);
        }
    }

    public function updatePriority(Request $request, Task $task)
    {
        $request->validate([
            'priority' => 'required|string|in:basse,moyenne,élevée'
        ]);

        try {
            $task->update(['priority' => $request->input('priority')]);
            return response()->json(['error' => false, 'message' => 'Priorité mise à jour avec succès', 'value' => $task]);
        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'message' => $th->getMessage()], 500);
        }
    }

    public function updateTitleAndDescription(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000'
        ]);

        try {
            $task->update([
                'title' => $request->input('title'),
                'description' => $request->input('description')
            ]);
            return response()->json(['error' => false, 'message' => 'Titre et description mis à jour avec succès', 'value' => $task]);
        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'message' => $th->getMessage()], 500);
        }
    }
}
