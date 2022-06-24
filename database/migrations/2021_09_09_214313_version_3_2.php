<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Version32 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('purchased_channels');

        Schema::table('device_records', function (Blueprint $table) {
            $table->dropColumn('purchased_channel_id');
        });

        Schema::table('part_records', function (Blueprint $table) {
            $table->dropColumn('purchased_channel_id');
        });

        Schema::table('software_records', function (Blueprint $table) {
            $table->dropColumn('purchased_channel_id');
        });

        Schema::table('service_records', function (Blueprint $table) {
            $table->dropColumn('purchased_channel_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
