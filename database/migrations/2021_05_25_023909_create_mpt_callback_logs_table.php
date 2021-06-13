<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMptCallbackLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mpt_callback_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id');
            $table->text('reqBody');
            $table->text('resBody');
            $table->string('status_code');
            $table->string('tranid');
            $table->string('service_id');
            $table->string('message');
            $table->string('action');
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
        Schema::dropIfExists('mpt_callback_logs');
    }
}
