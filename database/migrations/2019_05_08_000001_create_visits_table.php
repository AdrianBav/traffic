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

            $table->unsignedInteger('site_id');
            $table->unsignedInteger('ip_id');
            $table->unsignedInteger('agent_id');

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
