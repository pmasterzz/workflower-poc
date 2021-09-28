<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\TaskList;
use App\Models\Workflow;
use Faker\Generator as Faker;

$factory->define(TaskList::class, function (Faker $faker) {
    $workflow = Workflow::where('name', 'TaskListWorflow')->first();
    return [
        'serialized_workflow' => $workflow->serialized_workflow,
        'name' => $faker->name,
    ];
});
