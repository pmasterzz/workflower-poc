<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasklistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->boolean('completed')->default(false);
            $table->binary('serialized_workflow')->nullable();
            $table->string('state')->nullable();
            $table->timestamps();
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->boolean('completed')->default(false);
            $table->unsignedBigInteger('task_list_id');
            $table->timestamps();

            $table->foreign('task_list_id')->references('id')->on('task_lists');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('task_lists');
    }
}
