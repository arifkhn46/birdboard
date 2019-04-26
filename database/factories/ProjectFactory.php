<?php

use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(),
        'description' => $faker->paragraph(),
        'notes' => 'Foo bar notes',
        'owner_id' => factory(App\User::class),
    ];
});
