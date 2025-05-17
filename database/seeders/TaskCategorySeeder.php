<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaskCategory;

class TaskCategorySeeder extends Seeder
{
    public function run()
    {
        TaskCategory::create([
            'name' => 'Development',
        ]);

        TaskCategory::create([
            'name' => 'Design',
        ]);
    }
}
