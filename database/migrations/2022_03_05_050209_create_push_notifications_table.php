<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePushNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('push_notifications', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_new')->default(true);

            $table->string('body',  16000);
            $table->string('tittle');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_check')->default(false);
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('push_notification_event_id');
            $table->foreign('push_notification_event_id')->references('id')->on('push_notification_events');
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
        Schema::dropIfExists('push_notifications');
    }
}
