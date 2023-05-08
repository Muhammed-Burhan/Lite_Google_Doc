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
        Schema::create('post_user', function (Blueprint $table) {
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->foreignId('post_id');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            //composite primary key mean 2 value use used then it must be uniq
            $table->primary(['post_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_user');
    }
};
