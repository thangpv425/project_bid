<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBidTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bid', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('bid_id');
            $table->integer('bid_amount');
            $table->integer('real_bid_amount');
            $table->tinyInteger('status');
            $table->tinyInteger('bid_type');
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
        Schema::dropIfExists('user_bid');
    }
}
