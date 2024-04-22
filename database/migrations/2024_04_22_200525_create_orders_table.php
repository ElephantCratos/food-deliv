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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');

        });


        Schema::create('order_position', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extra_ingredients_id')->references('id')->on('ingredients');
            $table->string('description');
            $table->decimal('price', 10 , 2);
        });



        Schema::create('status', function (Blueprint $table) {
            $table->id();
            $table->string('name');

        });



        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('products_id')->references('id')->on('order_position');
            $table->foreignId('customer_id')->references('id')->on('users');
            $table->foreignId('courier_id')->references('id')->on('users');
            $table->foreignId('status_id')->references('id')->on('status');
            $table->decimal('price', 10, 2);
            $table->string('address');
            $table->string('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
