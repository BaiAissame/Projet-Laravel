<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProjectsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Project::select('name', 'description', 'created_at')->get();
    }

    public function headings(): array
    {
        return [
            'Nom',
            'Description',
            'Date de création',
        ];
    }
}
