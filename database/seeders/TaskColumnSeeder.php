<?php

namespace Database\Seeders;

use App\Models\ListTask;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class TaskColumnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ListTask::create([
            'name' => 'À faire',
            'project_id' => 1,
        ]);

        ListTask::create([
            'name' => 'En cours',
            'project_id' => 1,
        ]);

        ListTask::create([
            'name' => 'Fait',
            'project_id' => 1,
        ]);//
    }
}
