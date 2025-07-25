<?php
namespace Database\Factories;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
  protected $model = Task::class;

  public function definition()
  {
    return [
      'title' => $this->faker->words(3, true),
      'description' => $this->faker->sentence(),
      'list_task_id' => \App\Models\ListTask::factory(),
    ];
  }
}
