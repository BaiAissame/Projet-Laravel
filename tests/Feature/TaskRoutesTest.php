<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_tasks_list_route_displays_tasks()
    {
        $project = Project::factory()->create();
        $listTask = $project->listTasks()->create([
            'title' => 'Liste 1',
            'order' => 1,
            'color' => 'blue',
        ]);
        $task = Task::factory()->create(['list_task_id' => $listTask->id]);
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('tasks.list', $project));
        $response->assertStatus(200);
        $response->assertSee($task->title);
    }

    public function test_tasks_search_route_returns_results()
    {
        $project = Project::factory()->create();
        $listTask = $project->listTasks()->create([
            'title' => 'Liste 1',
            'order' => 1,
            'color' => 'blue',
        ]);
        $task = Task::factory()->create([
            'list_task_id' => $listTask->id,
            'title' => 'RechercheTest',
        ]);
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('tasks.search', ['q' => 'RechercheTest']));
        $response->assertStatus(200);
        $response->assertSee('RechercheTest');
    }
}
