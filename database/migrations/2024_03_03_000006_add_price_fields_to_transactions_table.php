<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('price_id')->nullable()->constrained('price_lists')->nullOnDelete();
            $table->decimal('quantity', 10, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['price_id']);
            $table->dropColumn(['price_id', 'quantity']);
        });
    }
}; 