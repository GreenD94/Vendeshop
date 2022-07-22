<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('address')->nullable();
            $table->string('city_name')->nullable();
            $table->string('city_id')->nullable();
            $table->string('state_name')->nullable();
            $table->string('state_id')->nullable();
            $table->string('street')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('deparment')->nullable();
            $table->string('phone_number')->nullable();
            $table->boolean('is_favorite')->default(false);

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
        Schema::dropIfExists('addresses');
    }
}
