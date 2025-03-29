<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    public function run()
    {
        Task::create([
            'title' => 'Task 1',
            'description' => 'Task 1 description',
            'project_id' => 1,
            'task_category_id' => 1,
            'task_priority_id' => 1,
        ]);

        Task::create([
            'title' => 'Task 2',
            'description' => 'Task 2 description',
            'project_id' => 2,
            'task_category_id' => 2,
            'task_priority_id' => 2,
        ]);
    }
}
