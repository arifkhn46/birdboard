<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Project;
use Facades\Tests\Setup\ProjectFactory;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_project_can_have_tasks()
    {
        $this->withoutExceptionHandling();

        $this->singIn();

        $project = factory(Project::class)->create(['owner_id' => auth()->id()]);

        $this->post($project->path() . '/tasks', ['body' => 'Test Tasks']);

        $this->get($project->path())
            ->assertSee('Test Tasks');
    }

    /** @test */
    public function a_task_can_be_updated()
    {
        
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), [ 
                'body' => 'changed',
                'completed' => true
            ]);

        $this->assertDatabaseHas('tasks',[
            'body' => 'changed',
            'completed' => true
        ]);
    }

    /** @test */
    public function only_the_owner_of_the_project_may_update_a_task()
    {
        $this->singIn();

        $project = factory(Project::class)->create();

        $task = $project->addTask('test task');

        $this->patch($task->path(), ['body' => 'changed'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }

    /** @test */
    public function only_the_owner_of_a_project_may_add_tasks()
    {
        $this->singIn();

        $project = factory(Project::class)->create();

        $this->post($project->path() . '/tasks', ['body' => 'Test Tasks'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Test Tasks']);
    }

    /** @test */
    public function a_task_requires_a_body()
    {
        $this->singIn();

        $project = auth()->user()->projects()->create(
            factory(Project::class)->raw()
        );

        $attributes = factory('App\Task')->raw(['body' => '']);

        $this->post($project->path() . '/tasks', $attributes)->assertSessionHasErrors('body');
    }
}
