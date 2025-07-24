<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);

        $this->call(TaskCategorySeeder::class);
        $this->call(TaskPrioritySeeder::class);

        $this->call(ProjectSeeder::class);

        $this->call(ListTaskSeeder::class);

        $this->call(TaskSeeder::class);

        $this->call(TaskUserSeeder::class);
    }
}
