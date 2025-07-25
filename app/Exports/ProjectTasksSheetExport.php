<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProjectTasksSheetExport implements FromArray, WithTitle, WithHeadings
{
    protected $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function array(): array
    {
        $rows = [];
        // Récupère toutes les tâches du projet (toutes les ListTask)
        $tasks = $this->project->listTasks->flatMap(function ($listTask) {
            return $listTask->tasks;
        });
        foreach ($tasks as $task) {
            $rows[] = [
                $task->title,
                $task->description,
                $task->created_at ? $task->created_at->format('Y-m-d H:i') : '',
                $task->due_date ? $task->due_date->format('Y-m-d H:i') : '',
                $task->assignes->pluck('name')->implode(', '),
                $task->priority ?? '',
                $task->category ?? '',
                $task->listTask ? $task->listTask->title : '', // Ajout du nom de la liste
            ];
        }
        return $rows;
    }

    public function headings(): array
    {
        return [
            'Titre',
            'Description',
            'Date de création',
            'Date limite',
            'Assignés',
            'Priorité',
            'Catégorie',
            'Liste', // Ajout de l'en-tête Liste
        ];
    }

    public function title(): string
    {
        return $this->project->name;
    }
} 