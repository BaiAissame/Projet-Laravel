<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    public function run()
    {
        Project::create([
            'name' => 'Project A',
            'description' => 'This is a sample project.',
            'user_id' => 1,
        ]);

        Project::create([
            'name' => 'Project B',
            'description' => 'This is another sample project.',
            'user_id' => 2,
        ]);
    }
}
