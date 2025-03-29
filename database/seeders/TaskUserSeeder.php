<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaskUser;

class TaskUserSeeder extends Seeder
{
    public function run()
    {
        TaskUser::create([
            'task_id' => 1,
            'user_id' => 1,
        ]);

        TaskUser::create([
            'task_id' => 2,
            'user_id' => 2,
        ]);
    }
}
