<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectModelTest extends TestCase
{
  use RefreshDatabase;

  public function test_project_can_be_created()
  {
    $project = Project::factory()->create([
      'name' => 'Projet Test',
      'slug' => 'projet-test',
    ]);
    $this->assertDatabaseHas('projects', [
      'name' => 'Projet Test',
      'slug' => 'projet-test',
    ]);
  }

  public function test_project_has_tasks()
  {
    $project = Project::factory()->create();
    $listTask = $project->listTasks()->create([
      'title' => 'Liste 1',
      'order' => 1,
      'color' => 'blue',
    ]);
    $task = $listTask->tasks()->create([
      'title' => 'Tâche liée',
      'description' => 'Description',
      'order' => 1,
    ]);
    $allTasks = $project->listTasks->flatMap->tasks;
    $this->assertContains($task->id, $allTasks->pluck('id')->all());
  }
}
