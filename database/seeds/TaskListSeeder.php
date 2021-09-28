<?php

use App\Models\TaskList;
use App\User;
use Illuminate\Database\Seeder;

class TaskListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $taskLists = factory(TaskList::class, 10)->create();

        foreach ($taskLists as $taskList) {
            factory(\App\Models\Task::class, 5)->create(['task_list_id' => $taskList->id]);
        }
    }
}
