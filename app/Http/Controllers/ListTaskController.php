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

        // Retourner le HTML de la nouvelle colonne Kanban
        $html = '<div class="kanban-list bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-4 w-80 flex-shrink-0 flex flex-col group border border-gray-200 dark:border-gray-700 hover:shadow-2xl transition-all duration-200" data-list-id="' . $listTask->id . '">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center gap-2">
                    <span class="drag-handle cursor-grab text-gray-400 hover:text-blue-500"><i class="fas fa-grip-vertical"></i></span>
                    <input class="font-bold text-lg bg-transparent border-none w-40 focus:ring-2 focus:ring-blue-400 rounded px-1 transition-all" value="' . $listTask->title . '" readonly onfocus="this.select()" />
                </div>
                <div class="relative">
                    <button class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 px-2 py-1 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-400" onclick="toggleListMenu(this)"><i class="fas fa-ellipsis-h"></i></button>
                    <div class="hidden absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 rounded-xl shadow-lg z-20 border border-gray-200 dark:border-gray-700 list-menu">
                        <button class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-red-600 font-semibold" onclick="deleteList(' . $listTask->id . ')"><i class="fas fa-trash mr-2"></i>Supprimer la liste</button>
                    </div>
                </div>
            </div>
            <div class="flex-1 space-y-3 min-h-[40px] task-list" style="min-height:40px;"></div>
            <form class="mt-4 flex" onsubmit="return createTask(event, ' . $listTask->id . ')">
                <input type="text" class="flex-1 rounded-l-lg border px-2 py-1 focus:ring-2 focus:ring-blue-400" placeholder="Nouvelle tâche..." required>
                <button class="bg-gradient-to-r from-blue-500 to-purple-600 text-white px-3 rounded-r-lg hover:from-blue-600 hover:to-purple-700 transition-all">+</button>
            </form>
        </div>';
        
        return response()->json(['html' => $html]);
    }


    public function updateOrder(Request $request)
    {

        $request->validate([
            'orderList' => 'required|array',
        ]);


        $orderData = $request->input('orderList');

        foreach ($orderData as $item) {
            ListTask::where('id', $item['listTaskId'])
                ->update(['order' => $item['order']]);
        }


        return response()->json([
            'message' => "ok",
            // 'newOrder' => $request->input('newOrder'),
        ]);
    }


    public function updateTitle(Request $request, ListTask $listTask)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        try {
            $listTask->update(['title' => $request->input('title')]);

            return response()->json([
                'message' => "ok",
                'newTitle' => $listTask->title,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => "Erreur lors de la mise à jour du titre de la liste : " . $e->getMessage(),
            ]);
        }
    }


    public function delete(ListTask $listTask)
    {
        try {
            $listTask->delete();
            return response()->json(['message' => 'Liste supprimée avec succès']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => "Erreur lors de la suppression de la liste : " . $e->getMessage(),
            ]);
        }
    }

}
