<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProjectsExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        $projects = Project::with(['listTasks.tasks.assignes'])->get();
        foreach ($projects as $project) {
            $sheets[] = new ProjectTasksSheetExport($project);
        }
        return $sheets;
    }
}
