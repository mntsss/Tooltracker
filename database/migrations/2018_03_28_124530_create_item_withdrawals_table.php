<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_withdrawals', function (Blueprint $table) {
            $table->increments('ItemWithdrawalID');
            $table->integer('ItemWithdrawalQuentity')->default(1);
            $table->boolean('ItemWithdrawalReturned')->default(0);
            $table->integer('ItemWithdrawalReturnedQuantity')->nullable();
            $table->integer('UserID')->nullable();
            $table->integer('ObjectID')->nullable();
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
        Schema::dropIfExists('item_withdrawals');
    }
}
