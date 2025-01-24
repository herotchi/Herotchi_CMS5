<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Consts\MediaConsts;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('media_flg')->unsigned();
            $table->string('image', 255);
            $table->string('alt', 255);
            $table->string('url', 255);
            $table->tinyInteger('release_flg')->default(MediaConsts::RELEASE_FLG_OFF)->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
