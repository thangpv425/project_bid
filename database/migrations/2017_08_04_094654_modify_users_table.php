<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ship_name', 50)->default(null);
            $table->string('ship_name_kana', 50)->default(null);
            $table->string('ship_zip', 10)->default(null);
            $table->string('ship_prefecture', 255)->default(null);
            $table->string('ship_address', 255)->default(null);
            $table->string('ship_tel', 50)->default(null);
            $table->string('post_office', 255)->default(null);
            $table->renameColumn('name','nickname');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
