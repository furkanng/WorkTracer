<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('company_name')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('tax_office')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('address');
            $table->decimal('balance', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}; 