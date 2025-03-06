<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('type', ['payment', 'debt']); // payment: ödeme, debt: borç
            $table->string('description')->nullable();
            $table->string('document_no')->nullable(); // Fiş/Fatura no
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_transactions');
    }
}; 