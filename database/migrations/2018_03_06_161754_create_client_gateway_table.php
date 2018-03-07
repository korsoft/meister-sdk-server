<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientGatewayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_gateway', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id');
            $table->string('name',120);
            $table->string('url',120);
            $table->smallInteger('auth_type')->default(0);
            $table->string('username',120);
            $table->string('password',120);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('client_id')->references('id')->on('client');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_gateway');
    }
}
