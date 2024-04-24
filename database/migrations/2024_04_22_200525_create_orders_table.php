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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });

        Schema::create('dish', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image_path');

            $table->foreignId('extra_ingredients_id')->references('id')->on('ingredients')->nullable();
            $table->decimal('price', 10 , 2);
            $table->timestamps();

        });

        Schema::create('order_position', function (Blueprint $table) {
            $table->id();

            $table->foreignId('selected_ingredients_id')->references('id')->on('ingredients');
            $table->foreignId('dish_id')->references('id')->on('dish');

            $table->decimal('price', 10, 2);
            $table->integer('quantity');
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

        Schema::table('dish', function (Blueprint $table) {
    $table->dropForeign(['extra_ingredients_id']);
    $table->dropColumn('extra_ingredients_id');
});
    }


    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
