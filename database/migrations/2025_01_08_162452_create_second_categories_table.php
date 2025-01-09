<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('second_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('first_category_id')->constrained(); 
            $table->string('name', 255);
            $table->timestamps();

            // first_category_id と name の組み合わせに UNIQUE 制約を追加
            $table->unique(['first_category_id', 'name'], 'unique_first_category_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('second_categories');
    }
};
