<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyDefaultValueForCurrentHighestPriceAndName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bids', function (Blueprint $table) {
            $table->integer('current_highest_price')->nullable()->change();
            $table->string('current_highest_bidder_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bids', function (Blueprint $table) {
            $table->integer('current_highest_price')->notnull()->change();
            $table->string('current_highest_bidder_name')->notnull()->change();
        });
    }
}
