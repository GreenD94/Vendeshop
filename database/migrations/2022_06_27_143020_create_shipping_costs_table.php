<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_costs', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(false);
            $table->double('price')->default(0.0);
            $table->double('price_percentage');
            $table->string("poblacion_origen");
            $table->string("poblacion_destino");
            $table->string("departamento_destino");
            $table->string("tipo_envio");
            $table->double('d2021_paq');
            $table->string("d2021_msj");
            $table->double('d1kg_msj');
            $table->double('d2kg_msj');
            $table->double('d3kj_msj');
            $table->double('d4kg_msj');
            $table->double('d5kg_msj');
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
        Schema::dropIfExists('shipping_costs');
    }
}
