<?php

namespace App\Http\Controllers;

use App\Models\ListTask;
use App\Models\Project;
use Illuminate\Http\Request;

class ListTaskController extends Controller
{
    public function create(Request $request, Project $projet)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $lastOrder = ListTask::where('project_id', $projet->id)->max('order');
        $newOrder = $lastOrder ? $lastOrder + 1 : 1;

        $listTask = ListTask::create([
            'title' => $request->input('title'),
            'order' => $newOrder,
            'project_id' => $projet->id,
        ]);

        $html = view('components.block-list-task', compact('listTask'))->render();
        return response()->json(['html' => $html]);
    }

    // public function update(Request $request, Task $task)
    // {
    //     $request->validate([
    //         'title' => 'required|string|max:255',
    //         'task_column_id' => 'required|exists:task_columns,id',
    //     ]);

    //     $task->update([
    //         'title' => $request->title,
    //         'task_column_id' => $request->task_column_id,
    //     ]);

    //     return response()->json(['message' => 'Tâche mise à jour avec succès', 'task' => $task]);
    // }

    // public function destroy(Task $task)
    // {
    //     $task->delete();
    //     return response()->json(['message' => 'Tâche supprimée avec succès']);
    // }

    // public function move(Request $request, Task $task)
    // {
    //     $request->validate([
    //         'task_column_id' => 'required|exists:task_columns,id',
    //         'order' => 'required|integer',
    //     ]);

    //     Task::where('task_column_id', $request->task_column_id)
    //         ->where('order', '>=', $request->order)
    //         ->increment('order');

    //     $task->update([
    //         'task_column_id' => $request->task_column_id,
    //         'order' => $request->order,
    //     ]);

    //     return response()->json(['message' => 'Tâche déplacée avec succès']);
    // }

    public function updateOrder(Request $request)
    {

        $request->validate([
            'listTaskId' => 'required|integer:exists:list_tasks,id',
            'order' => 'required|integer',
            'newOrder' => 'required|integer',
            'projetId' => 'required|integer:exists:projects,id',
        ]);

        if ($request->input('newOrder') == 0) response()->json([
            'message' => 'Ordre invalide',
            'error' => true
        ]);

        if ($request->input('order') == $request->input('newOrder')) {
            return response()->json([
                'message' => 'Aucun changement d\'ordre nécessaire',
                'error' => false
            ]);
        }

        if ($request->input('order') < $request->input('newOrder')) {
            ListTask::where('project_id', $request->input('projetId'))
                ->where('order', $request->input('newOrder'))
                ->decrement('order');

            ListTask::where('project_id', $request->input('projetId'))
                ->where('id', $request->input('listTaskId'))
                ->increment('order');
        } else {
            ListTask::where('project_id', $request->input('projetId'))
                ->where('order', $request->input('newOrder'))
                ->increment('order');

            ListTask::where('project_id', $request->input('projetId'))
                ->where('id', $request->input('listTaskId'))
                ->decrement('order');
        }

        $listTask = ListTask::where('id', $request->input('listTaskId'))
            ->where('project_id', $request->input('projetId'))
            ->firstOrFail();

        return response()->json([
            'message' => "ok", 
            'newOrder' => $request->input('newOrder'),
            'data' => $listTask
        ]);
    }
}
