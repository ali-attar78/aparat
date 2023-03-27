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
            $table->string('email',100)->unique()->nullable();
            $table->string('mobile',13)->unique()->nullable();
            $table->string('name',100);
            $table->string('password',100);
            $table->enum('type',\App\Models\User::TYPES)->default(\App\Models\User::TYPE_USER);
            $table->string('avatar',100)->nullable();
            $table->string('website')->nullable();
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
