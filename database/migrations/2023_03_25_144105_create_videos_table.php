<?php

use App\Models\Video;
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
        Schema::create('videos', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('channel_category_id');
            $table->string('slug', 50);
            $table->string('title');
            $table->text('info')->nullable();
            $table->integer('duration');
            $table->string('banner')->nullable();
            $table->boolean('enable_comments')->default(true);
            $table->enum('state',Video::STATES)->default(Video::PENDING);
            $table->timestamp('publish_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onDelete('set null')
                    ->onUpdate('cascade');

            $table->foreign('category_id')
                    ->references('id')->on('categories')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->foreign('channel_category_id')
                ->references('id')->on('categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
