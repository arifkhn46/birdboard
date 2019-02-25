<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Facades\Tests\Setup\ProjectFactory;
use App\Task;

class TriggerActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function creating_a_project()
    {
        $project = ProjectFactory::create();

        $this->assertCount(1, $project->activity);

        $this->assertEquals('created', $project->activity->first()->description);
    }

    /** @test */
    public function updating_a_project()
    {
        $project = ProjectFactory::create();

        $project->update(['title' => "changed"]);

        $this->assertCount(2, $project->activity);

        $this->assertEquals('updated', $project->activity->last()->description);
    }

    /** @test */
    public function creating_a_new_task()
    {
        $project = ProjectFactory::create();

        $project->addTask('Some task');

        $this->assertCount(2, $project->activity);

        tap($project->activity->last(), function($activtiy) {
            $this->assertEquals('created_task', $activtiy->description);
            $this->assertInstanceOf(Task::class, $activtiy->subject);
            $this->assertEquals('Some task', $activtiy->subject->body);
        });
    }

    /** @test */
    public function completing_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), [
                'body' => 'foobar',
                'completed' => true,
            ]);

        $this->assertCount(3, $project->activity);
        tap($project->activity->last(), function($activtiy) {
            $this->assertEquals('completed_task', $activtiy->description);
            $this->assertInstanceOf(Task::class, $activtiy->subject);
        });
    }

    /** @test */
    public function incompleting_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), [
                'body' => 'foobar',
                'completed' => true,
            ]);

        $this->assertCount(3, $project->activity);

        $this->patch($project->tasks->first()->path(), [
                'body' => 'foobar',
                'completed' => false,
            ]);

        $project->refresh();

        $this->assertCount(4, $project->activity);

        tap($project->activity->last(), function($activtiy) {
            $this->assertEquals('incompleted_task', $activtiy->description);
            $this->assertInstanceOf(Task::class, $activtiy->subject);
        });
    }

    /** @test */
    public function delete_a_task()
    {
        $project = ProjectFactory::withTasks(1)->create();

        $project->tasks[0]->delete();

        $this->assertCount(3, $project->activity);

        $this->assertEquals('deleted_task', $project->activity->last()->description);
    }
}