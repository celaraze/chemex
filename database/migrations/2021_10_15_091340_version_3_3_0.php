<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Version330 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('maintenance_records', function (Blueprint $table) {
            $table->dropColumn('item');
            $table->dropColumn('item_id');
            $table->string('asset_number')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maintenance_records', function (Blueprint $table) {
            $table->integer('item')->after('id');
            $table->integer('item_id')->after('item');
            $table->dropColumn('asset_number');
        });
    }
}
