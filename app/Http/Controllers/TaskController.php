<?php

namespace App\Http\Controllers;
use App\Models\Task;


use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function deplacer(Request $request, Task $task)
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
