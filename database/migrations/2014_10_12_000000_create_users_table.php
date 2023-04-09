<?php

use App\Models\User;
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
        Schema::create('users', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id();
            $table->string('name')->nullable();
            $table->string('email', 191)->unique()->nullable();
            $table->string('mobile', 13)->unique()->nullable();
            $table->string('password')->nullable();
            $table->enum('type', User::TYPES)->default(User::USER_TYPE);
            $table->string('avatar', 100)->nullable();
            $table->string('website', 100)->nullable();
            $table->string('verified_code', 6)->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
