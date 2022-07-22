<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->double('price')->default(0.0);
            $table->double('mock_price')->default(0.0);
            $table->double('credits')->default(0.0);
            $table->double('discount')->default(0.0);
            $table->unsignedBigInteger('cover_image_id')->nullable();
            $table->foreign('cover_image_id')->references('id')->on('images');
            $table->string('description')->default('');
            $table->string('name')->default('');
            $table->unsignedBigInteger('color_id')->nullable();
            $table->foreign('color_id')->references('id')->on('colors');
            $table->unsignedBigInteger('size_id')->nullable();
            $table->foreign('size_id')->references('id')->on('sizes');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');
            $table->unsignedBigInteger('ribbon_id')->nullable();
            $table->foreign('ribbon_id')->references('id')->on('ribbons');
            $table->boolean('is_available')->default(true);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}
