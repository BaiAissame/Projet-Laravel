<?php

namespace App\Repositories;

use App\Models\Task;
use App\Models\Project;
use App\Models\Column;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TaskRepository
{

    public function getKanbanData(Project $project): array
    {
        $columns = $project->columns()
            ->with([
                'tasks' => function ($query) {
                    $query->orderBy('position', 'asc');
                }
            ])
            ->orderBy('position', 'asc')
            ->get();

        $columns->each(function ($column) {
            $column->tasks->each(function ($task) {
                $task->load(['assignees', 'creator']);
            });
        });

        $users = $project->members()->get();

        $creatorIncluded = $users->contains('id', $project->creator_id);
        if (!$creatorIncluded) {
            $creator = $project->creator;
            if ($creator) {
                $users->push($creator);
            }
        }

        return [
            'columns' => $columns,
            'users' => $users
        ];
    }


    public function getTaskWithRelations(Project $project, Task $task): ?Task
    {
        if ($task->project_id !== $project->id) {
            return null;
        }

        return $task->load(['assignees', 'categories', 'creator']);
    }


    public function getRecentUpdates(Project $project, $since = 0): array
    {
        $sinceDate = $since ? Carbon::createFromTimestampMs($since) : now()->subMinutes(5);

        $tasks = $project->tasks()
            ->with(['column', 'assignees', 'creator'])
            ->where(function ($query) use ($sinceDate) {
                $query->where('updated_at', '>=', $sinceDate)
                    ->orWhere('created_at', '>=', $sinceDate);
            })
            ->get();

        $updatedTasks = $tasks->map(function ($task) {
            return [
                'id' => $task->id,
                'project_id' => $task->project_id,
                'column_id' => $task->column_id,
                'creator_id' => $task->creator_id,
                'title' => $task->title,
                'description' => $task->description,
                'priority' => $task->priority,
                'due_date' => $task->due_date,
                'completed_at' => $task->completed_at,
                'position' => $task->position,
                'created_at' => $task->created_at,
                'updated_at' => $task->updated_at,
                'column' => $task->column,
                'assignees' => $task->assignees
            ];
        });

        return [
            'updates' => $updatedTasks,
            'timestamp' => now()->timestamp * 1000,
            'task_count' => $updatedTasks->count(),
            'since' => $since,
            'since_date' => $sinceDate->toIso8601String()
        ];
    }


    public function getTasksForListView(Project $project): array
    {
        $tasks = $project->tasks()
            ->with(['column', 'assignees', 'categories', 'creator'])
            ->orderBy('created_at', 'desc')
            ->get();

        $users = $project->members()->get();

        $creatorIncluded = $users->contains('id', $project->creator_id);
        if (!$creatorIncluded) {
            $creator = $project->creator;
            if ($creator) {
                $users->push($creator);
            }
        }

        $columns = $project->columns()->orderBy('position', 'asc')->get();
        $categories = \App\Models\Category::where('project_id', $project->id)->get();

        return [
            'tasks' => $tasks,
            'users' => $users,
            'columns' => $columns,
            'categories' => $categories
        ];
    }
}