<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ItemSuspentionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_suspentions', function (Blueprint $table) {
            $table->increments('SuspentionID');
            $table->boolean('SuspentionWarrantyFix');
            $table->boolean('SuspentionUnwarrantedFix');
            $table->boolean('SuspentionReturned');
            $table->boolean('SuspentionWarningShowed');
            $table->boolean('SuspentionUnconfirmedReturn');
            $table->text('SuspentionNote');
            $table->integer('ItemID');
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
        Schema::dropIfExists('item_suspentions');
    }
}
