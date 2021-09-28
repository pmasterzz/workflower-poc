<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Models\Task::class, function (Faker $faker) {
    return [
        'name' => $faker->name(),
        'completed' => false,
    ];
});
