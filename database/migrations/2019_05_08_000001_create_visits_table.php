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

            $table->string('site');
            $table->string('ip');
            $table->string('agent');

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
