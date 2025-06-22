<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaskCategory;

class TaskCategorySeeder extends Seeder
{
    public function run()
    {
        TaskCategory::create(['name' => 'marketing']);
        TaskCategory::create(['name' => 'développement']);
        TaskCategory::create(['name' => 'communication']);
    }
}
