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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('video_id');
            $table->bigInteger('user_id');
            $table->bigInteger('parent_id')->nullable();
            $table->text('body');
            $table->timestamp('accepted_at')->nullable();

            $table->foreign('user_id')
            ->references('id')->on('users')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('parent_id')
            ->references('id')->on('comments')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('video_id')
            ->references('id')->on('videos')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
