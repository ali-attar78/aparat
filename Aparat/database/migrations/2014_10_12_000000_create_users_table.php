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
        Schema::create('users', function (Blueprint $table) {
            $table->engine='InnoDB';
            $table->id();
            $table->string('mobile',13)->unique();
            $table->string('email',100)->unique();
            $table->string('name',100);
            $table->string('password',100);
            $table->string('avatar',100);
            $table->string('website');
            $table->string('verified_code',6)->nullable();
            $table->timestamp('verify_at')->nullable();
            $table->timestamps();

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
