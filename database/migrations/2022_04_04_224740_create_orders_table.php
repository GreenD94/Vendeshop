<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('numero_guia')->nullable();

            $table->string('user_first_name')->nullable();
            $table->string('user_last_name')->nullable();
            $table->string('user_phone')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_birth_date')->nullable();
            $table->string('user_email_verified_at')->nullable();
            $table->string('user_avatar_id')->nullable();
            $table->string('user_avatar_url')->nullable();



            $table->string('address_id')->nullable();
            $table->string('address_address')->nullable();
            $table->string('address_city_id')->nullable();
            $table->string('address_city_name')->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_postal_code')->nullable();
            $table->string('address_deparment')->nullable();
            $table->string('address_phone_number')->nullable();
            $table->string('address_state_name')->nullable();
            $table->string('address_state_id')->nullable();

            $table->string('billing_address_id')->nullable();
            $table->string('billing_address_address')->nullable();
            $table->string('billing_address_city_id')->nullable();
            $table->string('billing_address_city_name')->nullable();
            $table->string('billing_address_street')->nullable();
            $table->string('billing_address_postal_code')->nullable();
            $table->string('billing_address_deparment')->nullable();
            $table->string('billing_address_phone_number')->nullable();
            $table->string('billing_address_state_id')->nullable();
            $table->string('billing_address_state_name')->nullable();



            $table->unsignedBigInteger('payment_type_id')->nullable();
            $table->string('payment_type_name')->nullable();

            $table->string('total');
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
        Schema::dropIfExists('orders');
    }
}
