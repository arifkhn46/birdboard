<?php

use Faker\Generator as Faker;
use App\Task;
use App\Project;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'body' => $faker->sentence(),
        'project_id' => function() {
            return factory(Project::class)->create()->id;
        }
    ];
});
