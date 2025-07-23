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

        $html = '<div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 w-80 flex-shrink-0 flex flex-col group border border-gray-200 dark:border-gray-700 hover:shadow-2xl transition-all duration-200 relative" data-list-id="' . $listTask->id . '" data-list-task-id="' . $listTask->id . '" data-color="">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center gap-2">
                    <span class="list-handle cursor-grab text-gray-400 hover:text-blue-500"><i class="fas fa-grip-vertical"></i></span>
                    <input class="font-bold text-lg bg-transparent border-none w-3/4 text-gray-900 dark:text-white" value="' . $listTask->title . '" readonly="">
                </div>
                <div class="flex items-center space-x-1">
                    <!-- Bouton ajouter tâche rapide -->
                    <button onclick="quickAddTask(\'' . $listTask->id . '\')" class="w-8 h-8 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/40 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-110">
                        <i class="fas fa-plus text-sm"></i>
                    </button>

                    <!-- Menu d\'options -->
                    <div class="relative">
                        <button class="column-menu-btn w-8 h-8 flex items-center justify-center text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200" data-column-id="' . $listTask->id . '">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <div class="column-menu hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg z-20 border border-gray-200 dark:border-gray-700">
                            <button class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium color-btn" data-column-id="' . $listTask->id . '">
                                <i class="fas fa-palette mr-2"></i>Changer la couleur
                            </button>
                            <button class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium" onclick="editColumnName(\'' . $listTask->id . '\')">
                                <i class="fas fa-edit mr-2"></i>Renommer
                            </button>
                            <div class="border-t border-gray-200 dark:border-gray-700"></div>
                            <button class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 text-red-600 font-semibold delete-btn" data-column-id="' . $listTask->id . '">
                                <i class="fas fa-trash mr-2"></i>Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-1 space-y-3 droppable-zone" data-colonne="' . $listTask->id . '">
                <div class="text-center py-8 text-gray-500 dark:text-gray-400 empty-state">
                    <i class="fas fa-inbox text-2xl mb-2"></i>
                    <p class="text-sm">Aucune tâche</p>
                    <button onclick="quickAddTask(\'' . $listTask->id . '\')" class="mt-2 text-blue-600 dark:text-blue-400 hover:underline text-sm">
                        Ajouter une tâche
                    </button>
                </div>
            </div>
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
                'listTask' => [
                    'id' => $listTask->id,
                    'title' => $listTask->title,
                    'color' => $listTask->color,
                    'order' => $listTask->order,
                    'project_id' => $listTask->project_id,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => "Erreur lors de la mise à jour du titre de la liste : " . $e->getMessage(),
            ]);
        }
    }

    public function changeColor(Request $request)
    {
        $request->validate([
            'listTaskId' => 'required|integer|exists:list_tasks,id',
            'color' => 'required|string|in:blue,green,red,yellow,purple,pink,orange,gray',
        ]);

        try {
            $listTask = ListTask::findOrFail($request->input('listTaskId'));
            if ($listTask instanceof \Illuminate\Database\Eloquent\Collection) {
                $listTask = $listTask->first();
            }
            $listTask->update(['color' => $request->input('color')]);

            return response()->json([
                'success' => true,
                'message' => 'Couleur mise à jour avec succès',
                'color' => $listTask ? $listTask->getAttribute('color') : null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => "Erreur lors du changement de couleur: " . $e->getMessage(),
            ]);
        }
    }

    public function updateColor(Request $request, $listTaskId)
    {
        $request->validate([
            'color' => 'required|string',
        ]);

        try {
            $listTask = ListTask::findOrFail($listTaskId);
            if ($listTask instanceof \Illuminate\Database\Eloquent\Collection) {
                $listTask = $listTask->first();
            }
            $listTask->update(['color' => $request->input('color')]);

            return response()->json([
                'success' => true,
                'message' => "Couleur mise à jour avec succès",
                'color' => $listTask ? $listTask->getAttribute('color') : null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => "Erreur lors de la mise à jour de la couleur: " . $e->getMessage(),
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
