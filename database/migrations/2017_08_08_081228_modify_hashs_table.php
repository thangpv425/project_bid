<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyHashsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hashs', function (Blueprint $table) {
           $table->increments('id')->unique();
           $table->dropColumn('hash_key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hashs', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->string('hash_key')->unique();
        });
    }
}
