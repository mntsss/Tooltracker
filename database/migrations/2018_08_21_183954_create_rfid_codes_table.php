<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRfidCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rfid_codes', function (Blueprint $table) {
            $table->increments('CodeID');
            $table->string('Code');
            $table->integer('ItemID');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rfid_codes');
    }
}
