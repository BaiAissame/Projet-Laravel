<?php

namespace App\Http\Controllers;
use App\Models\Task;


use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function index()
    {
        $tasks = Task::all();
        return response()->json(['tasks' => $tasks]);
    }

    public function show(Task $task)
    {
        return response()->json(['task' => $task]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'task_column_id' => 'required|exists:task_columns,id',
        ]);

        $task = Task::create([
            'title' => $request->title,
            'task_column_id' => $request->task_column_id,
            'order' => Task::where('task_column_id', $request->task_column_id)->max('order') + 1,
        ]);

        return response()->json(['message' => 'Tâche créée avec succès', 'task' => $task]);
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'task_column_id' => 'required|exists:task_columns,id',
        ]);

        $task->update([
            'title' => $request->title,
            'task_column_id' => $request->task_column_id,
        ]);

        return response()->json(['message' => 'Tâche mise à jour avec succès', 'task' => $task]);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['message' => 'Tâche supprimée avec succès']);
    }

    public function move(Request $request, Task $task)
    {
        $request->validate([
            'task_column_id' => 'required|exists:task_columns,id',
            'order' => 'required|integer',
        ]);

        Task::where('task_column_id', $request->task_column_id)
            ->where('order', '>=', $request->order)
            ->increment('order');

        $task->update([
            'task_column_id' => $request->task_column_id,
            'order' => $request->order,
        ]);

        return response()->json(['message' => 'Tâche déplacée avec succès']);
    }

}
