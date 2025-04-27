<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.*/
    public function up(): void
    {
        Schema::create('dish', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image_path');
            $table->unsignedBigInteger('category_id');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });

        Schema::create('order_position', function (Blueprint $table) {
            $table->id();

            $table->foreignId('dish_id')->references('id')->on('dish');
            
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->timestamps();
        });




      



        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->references('id')->on('users');
            $table->foreignId('courier_id')->nullable()->references('id')->on('users');
            $table->foreignId('status');
            $table->decimal('price', 10, 2);
            $table->string('address')->nullable();
            $table->string('comment')->nullable();
            $table->time('expected_at')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
