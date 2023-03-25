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
        Schema::create('followers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id1');
            $table->bigInteger('user_id2');
            $table->timestamps();

            $table->foreign('user_id1')
                    ->references('id')->on('users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            $table->foreign('user_id2')
                    ->references('id')->on('users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('followers');
    }
};
