<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_configs', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(false);
            $table->double('return_percentage')->nullable();
            $table->double('return_price')->nullable();
            $table->double('minimum_spend')->nullable();
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
        Schema::dropIfExists('ticket_configs');
    }
}
