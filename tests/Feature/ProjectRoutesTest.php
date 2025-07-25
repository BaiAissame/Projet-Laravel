<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectRoutesTest extends TestCase
{
  use RefreshDatabase;

  public function test_project_show_route_displays_project()
  {
    $project = Project::factory()->create(['name' => 'Projet Affichage']);
    $user = User::factory()->create();
    $project->members()->attach($user->id); // Associate user with project
    $this->actingAs($user);
    $response = $this->get(route('projet.show', ['projet' => $project->slug]));
    $response->assertStatus(200);
    $response->assertSee('Projet Affichage');
  }
}
