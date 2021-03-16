<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropCreatorColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('check_records', function (Blueprint $table) {
            $table->dropColumn('creator');
        });
        Schema::table('check_tracks', function (Blueprint $table) {
            $table->dropColumn('creator');
        });
        Schema::table('device_categories', function (Blueprint $table) {
            $table->dropColumn('creator');
        });
        Schema::table('device_records', function (Blueprint $table) {
            $table->dropColumn('creator');
        });
        Schema::table('device_tracks', function (Blueprint $table) {
            $table->dropColumn('creator');
        });
        Schema::table('hardware_categories', function (Blueprint $table) {
            $table->dropColumn('creator');
        });
        Schema::table('hardware_records', function (Blueprint $table) {
            $table->dropColumn('creator');
        });
        Schema::table('hardware_tracks', function (Blueprint $table) {
            $table->dropColumn('creator');
        });
        Schema::table('maintenance_records', function (Blueprint $table) {
            $table->dropColumn('creator');
        });
        Schema::table('purchased_channels', function (Blueprint $table) {
            $table->dropColumn('creator');
        });
        Schema::table('service_issues', function (Blueprint $table) {
            $table->dropColumn('creator');
        });
        Schema::table('service_records', function (Blueprint $table) {
            $table->dropColumn('creator');
        });
        Schema::table('service_tracks', function (Blueprint $table) {
            $table->dropColumn('creator');
        });
        Schema::table('software_categories', function (Blueprint $table) {
            $table->dropColumn('creator');
        });
        Schema::table('software_records', function (Blueprint $table) {
            $table->dropColumn('creator');
        });
        Schema::table('software_tracks', function (Blueprint $table) {
            $table->dropColumn('creator');
        });
        Schema::table('staff_departments', function (Blueprint $table) {
            $table->dropColumn('creator');
        });
        Schema::table('staff_records', function (Blueprint $table) {
            $table->dropColumn('creator');
        });
        Schema::table('vendor_records', function (Blueprint $table) {
            $table->dropColumn('creator');
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
