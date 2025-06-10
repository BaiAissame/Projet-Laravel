<?php

namespace App\Http\Controllers;

use App\Models\ListTask;
use Illuminate\Http\Request;
use App\Models\Task;

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
            
            $task->delete();
            return response()->json(['error' => false, 'message' => 'Tâche supprimée avec succès']);
        } catch (\Throwable $th) {
            return response()->json(['error' => true, 'message' => $th]);
        }
    }
}
