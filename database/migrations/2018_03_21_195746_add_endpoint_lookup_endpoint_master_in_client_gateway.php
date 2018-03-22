<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEndpointLookupEndpointMasterInClientGateway extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_gateway', function (Blueprint $table) {
            $table->text('endpoint_lookup')->nullable();
            $table->text('endpoint_master')->nullable();
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
            $table->dropColumn('endpoint_lookup');
            $table->dropColumn('endpoint_master');
        });
    }
}
