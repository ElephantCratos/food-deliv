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
        Schema::create('order_and_order_position', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('position_id');
            $table->timestamps();


            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('position_id')->references('id')->on('order_position')->onDelete('cascade');


            $table->primary(['order_id', 'position_id']);

        });

         Schema::create('dish_and_ingredients', function (Blueprint $table) {
            $table->unsignedBigInteger('dish_id');
            $table->unsignedBigInteger('ingredients_id');
            $table->timestamps();


            $table->foreign('dish_id')->references('id')->on('dish')->onDelete('cascade');
            $table->foreign('ingredients_id')->references('id')->on('ingredients')->onDelete('cascade');


            $table->primary(['dish_id', 'ingredients_id']);

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_and_order_position');

        Schema::dropIfExists('dish_and_ingredients');
    }
};
