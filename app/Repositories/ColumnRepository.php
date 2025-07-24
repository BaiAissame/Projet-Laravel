<?php

namespace App\Repositories;

use App\Models\Column;
use App\Models\Project;

class ColumnRepository
{

    public function getMaxPosition(int $projectId): int
    {
        return Column::where('project_id', $projectId)->max('position') ?? 0;
    }
    

    public function getColumnCount(int $projectId): int
    {
        return Column::where('project_id', $projectId)->count();
    }
    

    public function findForProject(int $columnId, int $projectId): ?Column
    {
        return Column::where('id', $columnId)
            ->where('project_id', $projectId)
            ->first();
    }
} 