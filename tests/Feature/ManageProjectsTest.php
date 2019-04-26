<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Project;
use Facades\Tests\Setup\ProjectFactory;

class ProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;


    /** @test */
    public function guests_can_not_create_porjects()
    {
        $project = factory('App\Project')->create();

        $this->post('/projects', $project->toArray())->assertRedirect('login');

        $this->get('/projects/create')->assertRedirect('login');

        $this->get($project->path())->assertRedirect('login');

        $this->get($project->path() . '/edit')->assertRedirect('login');
    }

    /** @test */
    public function guest_may_not_view_projects()
    {
        $this->get('/projects')->assertRedirect('login');
    }


    /** @test */
    public function guest_cannot_view_a_single_projects()
    {
        $project = factory('App\Project')->create();

        $this->get($project->path())->assertRedirect('login');
    }

    /** @test */
    public function a_user_can_create_a_project()
    {

        $this->signIn();

        $this->get('/projects/create')->assertOk();

        $this->followingRedirects()
            ->post('/projects', $attributes = factory(Project::class)->raw())
            ->assertSee(str_limit($attributes['title'], 10))
            ->assertSee(str_limit($attributes['description'], 100))
            ->assertSee($attributes['notes']);
    }

    /** @test */
    public function a_user_can_update_a_project()
    {
        $project = ProjectFactory::create();

        $attributes = [
            'notes' => 'Changed',
            'title' => 'Changed',
            'description' => "Some Description here",
        ];

        $this->actingAs($project->owner);

        $this->patch($project->path(), $attributes)
            ->assertRedirect($project->path());

        $this->get($project->path(). '/edit')->assertOk();

        $this->assertDatabaseHas('projects', $attributes);
    }

    /** @test */
    public function a_user_can_delete_a_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner);

        $this->delete($project->path())
            ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', $project->only('id'));
    }

    /** @test */
    public function a_user_can_see_all_projects_they_have_been_invited_to_on_their_dashboard()
    {
        $user = $this->signIn();

        $project = tap(ProjectFactory::create())->invite($user);

        $this->get('/projects')
            ->assertSee(str_limit($project->title, 10));
    }
    /** @test */
    public function unauthorized_user_cannot_delete_a_project()
    {
        $project = ProjectFactory::create();

        $this->delete($project->path())
            ->assertRedirect('/login');

        $user = $this->signIn();

        $this->delete($project->path())
             ->assertStatus(403);

        $project->invite($user);

        $this->actingAs($user)->delete($project->path())->assertStatus(403);
    }

    /** @test */
    public function a_user_can_view_a_project()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner);

        $this->get($project->path())
            ->assertSee(str_limit($project->title, 10))
            ->assertSee(str_limit($project->description, 100));
    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_projects_of_others()
    {
        $this->be(factory('App\User')->create());

        $project = factory('App\Project')->create();

        $this->get($project->path())->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_user_cannot_update_the_projects_of_others()
    {
        $this->be(factory('App\User')->create());

        $project = factory('App\Project')->create();

        $this->patch($project->path(), ['notes' => 'Changed'])->assertStatus(403);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $this->signIn();

        $attributes = factory('App\Project')->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $this->signIn();

        $attributes = factory('App\Project')->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }
}
