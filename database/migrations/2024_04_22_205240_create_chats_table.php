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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_1_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_2_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        
            $table->unique(['user_1_id', 'user_2_id']); // уникальная пара
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
