<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->string('quantity')->nullable();
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->string('price')->nullable();
            $table->string('stock_id')->nullable();
            $table->string('mock_price')->nullable();
            $table->string('credits')->nullable();
            $table->string('discount')->nullable();
            $table->string('cover_image_id')->nullable();
            $table->string('cover_image_url')->nullable();

            $table->string('description')->nullable();
            $table->string('name')->nullable();
            $table->string('color_id')->nullable();
            $table->string('color_hex')->nullable();
            $table->string('size_id')->nullable();
            $table->string('size_size')->nullable();







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
        Schema::dropIfExists('order_details');
    }
}
