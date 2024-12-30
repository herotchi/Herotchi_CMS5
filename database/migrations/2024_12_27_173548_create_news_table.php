<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Consts\NewsConsts;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->tinyInteger('link_flg')->unsigned();
            $table->string('url', 255)->nullable();
            $table->text('overview')->nullable();
            $table->date('release_date');
            $table->tinyInteger('release_flg')->default(NewsConsts::RELEASE_FLG_OFF)->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
