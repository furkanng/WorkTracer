<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // price_lists tablosuna brand_id kolonu ekleme
        Schema::table('price_lists', function (Blueprint $table) {
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('price_lists', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropColumn('brand_id');
        });
        
        Schema::dropIfExists('brands');
    }
}; 