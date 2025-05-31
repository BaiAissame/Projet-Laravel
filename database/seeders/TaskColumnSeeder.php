<?php

namespace Database\Seeders;

use App\Models\TaskColumn;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class TaskColumnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TaskColumn::create([
            'name' => 'À faire',
            'project_id' => 1,
        ]);

        TaskColumn::create([
            'name' => 'En cours',
            'project_id' => 1,
        ]);

        TaskColumn::create([
            'name' => 'Fait',
            'project_id' => 1,
        ]);//
    }
}
