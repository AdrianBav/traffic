<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('traffic')->create('visits', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('site_id');
            $table->foreign('site_id')->references('id')->on('sites');

            $table->unsignedBigInteger('ip_id');
            $table->foreign('ip_id')->references('id')->on('ips');

            $table->unsignedBigInteger('agent_id');
            $table->foreign('agent_id')->references('id')->on('agents');

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
        Schema::connection('traffic')->dropIfExists('visits');
    }
}
