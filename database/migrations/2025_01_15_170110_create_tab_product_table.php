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
        Schema::create('tab_product', function (Blueprint $table) {
            $table->foreignId('tab_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->timestamps();

            $table->primary(['tab_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tab_product');
    }
};
