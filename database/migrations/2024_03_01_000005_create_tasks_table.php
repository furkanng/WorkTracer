<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('technician_id')->constrained('users');
            $table->foreignId('task_type_id')->constrained();
            $table->string('title');
            $table->text('description');
            $table->text('address');
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->dateTime('scheduled_date')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}; 