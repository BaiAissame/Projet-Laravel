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
            'list_task_id' => 1,
            'user_id' => 1,
            'category' => 'marketing',
            'priority' => 'élevée',
            'order' => 1,
        ]);

        Task::create([
            'title' => 'Task 2', 
            'description' => 'Task 2 description',
            'list_task_id' => 2,
            'user_id' => 2,
            'category' => 'développement',
            'priority' => 'moyenne',
            'order' => 1,
        ]);

        Task::create([
            'title' => 'Task 3',
            'description' => 'Task 3 description', 
            'list_task_id' => 3,
            'user_id' => 1,
            'category' => 'communication',
            'priority' => 'basse',
            'order' => 1,
        ]);
    }
}
