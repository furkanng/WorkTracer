<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customer_transactions', function (Blueprint $table) {
            $table->foreignId('user_id')->after('customer_id')->constrained();
        });
    }

    public function down()
    {
        Schema::table('customer_transactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}; 