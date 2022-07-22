<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->string('quantity')->nullable();
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->string('description')->nullable();
            $table->string('value')->nullable();
            $table->string('reference_code')->nullable();
            $table->string('tax_return_base')->nullable();
            $table->string('currency')->nullable();
            $table->string('buyer_id')->nullable();
            $table->string('buyer_name')->nullable();
            $table->string('buyer_email')->nullable();
            $table->string('buyer_contact_phone')->nullable();
            $table->string('buyer_dni')->nullable();
            $table->string('buyer_street')->nullable();
            $table->string('buyer_street_2')->nullable();
            $table->string('buyer_city')->nullable();
            $table->string('buyer_state')->nullable();
            $table->string('buyer_country')->nullable();
            $table->string('buyer_postal_code')->nullable();
            $table->string('buyer_phone')->nullable();

            $table->string('payer_id')->nullable();
            $table->string('payer_name')->nullable();
            $table->string('payer_email')->nullable();
            $table->string('payer_contact_phone')->nullable();
            $table->string('payer_dni')->nullable();
            $table->string('payer_street')->nullable();
            $table->string('payer_street_2')->nullable();
            $table->string('payer_city')->nullable();
            $table->string('payer_state')->nullable();
            $table->string('payer_country')->nullable();
            $table->string('payer_postal_code')->nullable();
            $table->string('payer_phone')->nullable();

            $table->string('payment_method')->nullable();
            $table->string('installments_number')->nullable();
            $table->string('country')->nullable();


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
