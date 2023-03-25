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
        Schema::create('video_reports', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('report_category_id');
            $table->bigInteger('user_id');
            $table->bigInteger('video_id');
            $table->text('info');
            $table->smallInteger('first_time')->nullable();
            $table->smallInteger('second_time')->nullable();
            $table->smallInteger('third_time')->nullable();

            $table->foreign('user_id')
            ->references('id')->on('users')
            ->onDelete('cascade')
            ->onUpdate('cascade');

            $table->foreign('report_category_id')
            ->references('id')->on('video_report_categoies')
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
        Schema::dropIfExists('video_reports');
    }
};
