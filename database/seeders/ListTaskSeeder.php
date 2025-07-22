<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ListTask;

class ListTaskSeeder extends Seeder
{
    public function run()
    {
        ListTask::create([
            'title' => 'To Do',
            'project_id' => 1,
            'order' => 1,
            'color' => '#3498db',
        ]);

        ListTask::create([
            'title' => 'In Progress',
            'project_id' => 1,
            'order' => 2,
            'color' => '#f39c12',
        ]);

        ListTask::create([
            'title' => 'Done',
            'project_id' => 1,
            'order' => 3,
            'color' => '#2ecc71',
        ]);

        ListTask::create([
            'title' => 'To Do',
            'project_id' => 2,
            'order' => 1,
            'color' => '#3498db',
        ]);
    }
}
