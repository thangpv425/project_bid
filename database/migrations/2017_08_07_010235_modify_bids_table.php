<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyBidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bids', function (Blueprint $table) {
            $table->dropColumn('user_got_id');
            $table->renameColumn('cost_max_current','current_highest_price');
            $table->string('current_highest_bidder_name')->after('cost_max_current');
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
            $table->integer('user_got_id');
            $table->dropColumn('current_highest_bidder_name');
            $table->renameColumn('current_highest_price', 'cost_max_current');
        });
    }
}
