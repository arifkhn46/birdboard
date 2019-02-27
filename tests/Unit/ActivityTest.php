<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Project as Project;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_user()
    {
        $project = factory(Project::class)->create();

        $this->assertInstanceOf(User::class, $project->activity->first()->owner);
    }
}
