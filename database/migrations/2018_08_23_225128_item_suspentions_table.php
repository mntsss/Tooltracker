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
            $table->boolean('SuspentionWarrantyFix')->defaut(0);
            $table->boolean('SuspentionUnwarrantedFix')->defaut(0);
            $table->boolean('SuspentionReturned')->defaut(0);
            $table->boolean('SuspentionWarningShowed')->defaut(0);
            $table->boolean('SuspentionUnconfirmedReturn')->defaut(0);
            $table->text('SuspentionNote')->nullable();
            $table->string('SuspentionConfirmCode')->nullable();
            $table->integer('UserID')->nullable();
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
