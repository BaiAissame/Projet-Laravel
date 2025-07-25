<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskModelTest extends TestCase
{
  use RefreshDatabase;

  public function test_task_can_be_created()
  {
    $task = Task::factory()->create([
      'title' => 'Test Task',
      'description' => 'Description de test',
    ]);
    $this->assertDatabaseHas('tasks', [
      'title' => 'Test Task',
      'description' => 'Description de test',
    ]);
  }

  public function test_task_belongs_to_project()
  {
    $project = Project::factory()->create();
    $listTask = $project->listTasks()->create([
      'title' => 'Liste 1',
    ]);
    $task = $listTask->tasks()->create([
      'title' => 'Test Task',
      'description' => 'Description de test',
    ]);
    $this->assertEquals($project->id, $task->listTask->project->id);
  }
}
