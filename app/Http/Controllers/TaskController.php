<?php

namespace App\Http\Controllers;

use App\Models\ListTask;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\TaskComment;
use App\Models\TaskTag;
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
            
            // Retourner le HTML de la nouvelle tâche au format Kanban
            $html = '<div class="bg-blue-50 dark:bg-blue-900 rounded-lg p-3 shadow cursor-pointer" onclick="openTaskModal(' . $task->id . ')">
                <div class="font-semibold">' . $task->title . '</div>
                <div class="text-xs text-gray-500">' . ($task->priorite ?? '') . '</div>
            </div>';
            
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

    // Nouvelles méthodes pour la modal moderne

    public function show(Task $task)
    {
        try {
            $task->load([
                'assignes:id,name,email',
                'listTask:id,title,project_id',
                'comments' => function($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'comments.user:id,name',
                'tags:id,name'
            ]);

            return response()->json([
                'success' => true,
                'task' => $task
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement de la tâche'
            ], 500);
        }
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|exists:list_tasks,id',
            'priority' => 'nullable|in:basse,moyenne,haute',
            'category' => 'nullable|in:marketing,développement,communication',
            'due_date' => 'nullable|date',
            'tags' => 'nullable|array'
        ]);

        try {
            $task->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'list_task_id' => $request->input('status'),
                'priorite' => $request->input('priority'),
                'categorie' => $request->input('category'),
                'date_limite' => $request->input('due_date'),
            ]);

            // Mettre à jour les tags
            if ($request->has('tags')) {
                $task->tags()->delete(); // Supprimer les anciens tags
                foreach ($request->input('tags') as $tagName) {
                    $task->tags()->create(['name' => $tagName]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Tâche mise à jour avec succès',
                'task' => $task->fresh(['assignes', 'listTask', 'comments.user', 'tags'])
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour'
            ], 500);
        }
    }

    public function addAssignee(Request $request, Task $task)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        try {
            // Vérifier si l'utilisateur n'est pas déjà assigné
            $existingAssignment = TaskUser::where('task_id', $task->id)
                ->where('user_id', $request->input('user_id'))
                ->first();

            if ($existingAssignment) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet utilisateur est déjà assigné à cette tâche'
                ], 400);
            }

            $taskUser = TaskUser::create([
                'task_id' => $task->id,
                'user_id' => $request->input('user_id'),
            ]);

            $assignee = $taskUser->user;

            return response()->json([
                'success' => true,
                'message' => 'Assigné ajouté avec succès',
                'assignee' => $assignee
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout de l\'assigné'
            ], 500);
        }
    }

    public function removeAssignee(Task $task, $userId)
    {
        try {
            // Vérifier si l'utilisateur actuel peut retirer cet assigné
            // (seul le créateur de la tâche ou l'assigné lui-même peut se retirer)
            if ($task->user_id !== Auth::id() && Auth::id() != $userId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'êtes pas autorisé à retirer cet assigné'
                ], 403);
            }

            TaskUser::where('task_id', $task->id)
                ->where('user_id', $userId)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Assigné retiré avec succès'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du retrait de l\'assigné'
            ], 500);
        }
    }

    public function addComment(Request $request, Task $task)
    {
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        try {
            $comment = TaskComment::create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'content' => $request->input('content'),
            ]);

            $comment->load('user');

            return response()->json([
                'success' => true,
                'message' => 'Commentaire ajouté avec succès',
                'comment' => $comment
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout du commentaire'
            ], 500);
        }
    }

    public function removeComment(Task $task, TaskComment $comment)
    {
        try {
            // Vérifier si l'utilisateur peut supprimer ce commentaire
            if ($comment->user_id !== Auth::id() && $task->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous n\'êtes pas autorisé à supprimer ce commentaire'
                ], 403);
            }

            $comment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Commentaire supprimé avec succès'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du commentaire'
            ], 500);
        }
    }

    public function addTag(Request $request, Task $task)
    {
        $request->validate([
            'name' => 'required|string|max:50'
        ]);

        try {
            $tag = TaskTag::create([
                'task_id' => $task->id,
                'name' => $request->input('name'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tag ajouté avec succès',
                'tag' => $tag
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'ajout du tag'
            ], 500);
        }
    }

    public function removeTag(Task $task, TaskTag $tag)
    {
        try {
            $tag->delete();

            return response()->json([
                'success' => true,
                'message' => 'Tag supprimé avec succès'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du tag'
            ], 500);
        }
    }
}
