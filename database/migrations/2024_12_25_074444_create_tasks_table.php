<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable(); // Allow description to be nullable
            $table->date('due_date');
            $table->enum('priority', ['Low', 'Medium', 'High']);
            $table->string('status')->default('Pending');
            $table->unsignedBigInteger('user_id'); // Add user_id for task ownership
            $table->timestamps();
            
            // Foreign key constraint for user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}