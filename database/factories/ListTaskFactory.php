<?php
namespace Database\Factories;

use App\Models\ListTask;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListTaskFactory extends Factory
{
  protected $model = ListTask::class;

  public function definition()
  {
    return [
      'title' => $this->faker->words(2, true),
      'project_id' => Project::factory(),
    ];
  }
}
