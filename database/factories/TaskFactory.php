<?php

use Faker\Generator as Faker;
use App\Task;
use App\Project;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'body' => $faker->sentence(),
        'completed' => false,
        'project_id' => factory(Project::class),
    ];
});
