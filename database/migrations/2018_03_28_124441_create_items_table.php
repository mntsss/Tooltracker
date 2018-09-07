<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('ItemID');
            $table->string('ItemName');
            $table->integer('ItemQuantity')->default(1);
            $table->boolean('ItemConsumable')->default(0);
            $table->string('ItemImage')->nullable();
            $table->boolean('ItemDeleted')->default(0);
            $table->string('ItemWarranty')->nullable();
            $table->string('ItemPurchase')->nullable();
            $table->integer('ItemGroupID');
            $table->string('ItemIdNumber')->nullable();
            $table->text('ItemNote')->nullable();
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
        Schema::dropIfExists('items');
    }
}
