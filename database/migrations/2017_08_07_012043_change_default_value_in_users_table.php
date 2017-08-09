<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDefaultValueInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('ship_name', 50)->nullable()->change();
            $table->string('ship_name_kana', 50)->nullable()->change();
            $table->string('ship_zip', 10)->nullable()->change();
            $table->string('ship_prefecture', 255)->nullable()->change();
            $table->string('ship_address', 255)->nullable()->change();
            $table->string('ship_tel', 50)->nullable()->change();
            $table->string('post_office', 255)->nullable()->change();
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
            $table->string('ship_name', 50)->notnull()->change();
            $table->string('ship_name_kana', 50)->notnull()->change();
            $table->string('ship_zip', 10)->notnull()->change();
            $table->string('ship_prefecture', 255)->notnull()->change();
            $table->string('ship_address', 255)->notnull()->change();
            $table->string('ship_tel', 50)->notnull()->change();
            $table->string('post_office', 255)->notnull()->change();
        });
    }
}
