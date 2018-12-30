<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Item;
class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->down();
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('consumable')->default(0);
            $table->string('acquired_from')->nullable();
            $table->string('warranty_date')->nullable();
            $table->string('purchase_date')->nullable();
            $table->integer('group_id')->->nullable();;
            $table->integer('storage_id')->->nullable();
            $table->string('identification')->nullable();
            $table->string('status')->default(Item::ITEM_IN_STORAGE);
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
