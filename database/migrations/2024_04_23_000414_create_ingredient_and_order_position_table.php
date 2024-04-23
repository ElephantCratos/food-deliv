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
        Schema::create('ingredient_and_order_position', function (Blueprint $table) {
            $table->unsignedBigInteger('order_position_id');
            $table->unsignedBigInteger('ingredient_id');
            $table->timestamps();


            $table->foreign('order_position_id')->references('id')->on('order_position')->onDelete('cascade');
            $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('cascade');


            $table->primary(['order_position_id', 'ingredient_id']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_and_order_position');
    }
};
