<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('ClientID');
            $table->string('ClientName');
            $table->string('ClientStatus');
            $table->string('ClientPhone');
            $table->string('ClientEmail');
            $table->string('ClientContactName');
            $table->string('ClientIdentificationHash');
            $table->date('ClientPayedTill');
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
        Schema::dropIfExists('clients');
    }
}
