<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRentedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rented_items', function (Blueprint $table) {
            $table->increments('RentedItemID');
            $table->string('RentedItemName');
            $table->text('RentedItemNote')->nullable();
            $table->string('RentedItemDate')->nullable();
            $table->decimal('RentedItemDailyPrice',8,2);
            $table->integer('ObjectID')->nullable();
            $table->boolean('RentedItemReturned')->default(0);
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
        Schema::dropIfExists('rented_items');
    }
}
