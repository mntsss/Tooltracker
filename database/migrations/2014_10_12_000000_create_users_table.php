<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('UserID');
            $table->string('email')->unique();
            $table->string('Username');
            $table->string('UserPhone')->nullable();
            $table->string('UserRole');
            $table->string('password');
            $table->boolean('UserDeleted')->default(0);
            $table->string('UserLastSeen')->nullable();
            $table->string('UserRFIDCode')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
