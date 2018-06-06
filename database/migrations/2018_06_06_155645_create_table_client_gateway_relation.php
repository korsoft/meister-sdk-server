<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableClientGatewayRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_gateway_relation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id');
            $table->integer('gateway_id');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('client_id')->references('id')->on('client');
            $table->foreign('gateway_id')->references('id')->on('client_gateway');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_gateway_relation');
    }
}
