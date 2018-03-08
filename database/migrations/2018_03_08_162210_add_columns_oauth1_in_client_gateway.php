<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsOauth1InClientGateway extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_gateway', function (Blueprint $table) {
            $table->text('consumer_key')->nullable();
            $table->text('consumer_secret')->nullable();
            $table->text('token')->nullable();
            $table->text('token_secret')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_gateway', function (Blueprint $table) {
            $table->dropColumn('consumer_key');
            $table->dropColumn('consumer_secret');
            $table->dropColumn('token');
            $table->dropColumn('token_secret');
        });
    }
}
