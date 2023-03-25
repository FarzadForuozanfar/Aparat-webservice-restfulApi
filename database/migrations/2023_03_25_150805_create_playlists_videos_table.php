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
        Schema::create('playlists_videos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('playlist_id');
            $table->bigInteger('video_id');

            $table->timestamps();
            $table->foreign('playlist_id')
            ->references('id')->on('playlist')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('video_id')
            ->references('id')->on('videos')
            ->onDelete('cascade')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlists_videos');
    }
};
