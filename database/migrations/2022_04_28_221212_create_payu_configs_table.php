<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayuConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payu_configs', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(false);
            $table->string('api_key');
            $table->string('api_login');
            $table->string('merchant_id');
            $table->boolean('is_test')->default(false);
            $table->string('payments_custom_url');
            $table->string('reports_custom_url');
            $table->string('account_id');
            $table->text('description');
            $table->string('tax_value');
            $table->string('tax_return_base');
            $table->string('currency');
            $table->string('installments_number');
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
        Schema::dropIfExists('payu_configs');
    }
}
