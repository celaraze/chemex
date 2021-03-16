<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateExtendedFieldsColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('device_records', function (Blueprint $table) {
            $table->string('extended_fields')->nullable();
        });
        Schema::table('device_categories', function (Blueprint $table) {
            $table->string('extended_fields')->nullable();
        });
        Schema::table('device_tracks', function (Blueprint $table) {
            $table->string('extended_fields')->nullable();
        });

        Schema::table('part_records', function (Blueprint $table) {
            $table->string('extended_fields')->nullable();
        });
        Schema::table('part_categories', function (Blueprint $table) {
            $table->string('extended_fields')->nullable();
        });
        Schema::table('part_tracks', function (Blueprint $table) {
            $table->string('extended_fields')->nullable();
        });

        Schema::table('software_records', function (Blueprint $table) {
            $table->string('extended_fields')->nullable();
        });
        Schema::table('software_categories', function (Blueprint $table) {
            $table->string('extended_fields')->nullable();
        });
        Schema::table('software_tracks', function (Blueprint $table) {
            $table->string('extended_fields')->nullable();
        });

        Schema::table('service_records', function (Blueprint $table) {
            $table->string('extended_fields')->nullable();
        });
        Schema::table('service_tracks', function (Blueprint $table) {
            $table->string('extended_fields')->nullable();
        });
        Schema::table('service_issues', function (Blueprint $table) {
            $table->string('extended_fields')->nullable();
        });

        Schema::table('consumable_records', function (Blueprint $table) {
            $table->string('extended_fields')->nullable();
        });
        Schema::table('consumable_categories', function (Blueprint $table) {
            $table->string('extended_fields')->nullable();
        });
        Schema::table('consumable_tracks', function (Blueprint $table) {
            $table->string('extended_fields')->nullable();
        });

        Schema::table('todo_records', function (Blueprint $table) {
            $table->string('extended_fields')->nullable();
        });
        Schema::table('depreciation_rules', function (Blueprint $table) {
            $table->string('extended_fields')->nullable();
        });
        Schema::table('purchased_channels', function (Blueprint $table) {
            $table->string('extended_fields')->nullable();
        });
        Schema::table('vendor_records', function (Blueprint $table) {
            $table->string('extended_fields')->nullable();
        });
        Schema::table('maintenance_records', function (Blueprint $table) {
            $table->string('extended_fields')->nullable();
        });
        Schema::table('check_records', function (Blueprint $table) {
            $table->string('extended_fields')->nullable();
        });
        Schema::table('check_tracks', function (Blueprint $table) {
            $table->string('extended_fields')->nullable();
        });
        Schema::table('departments', function (Blueprint $table) {
            $table->string('extended_fields')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('device_records', function (Blueprint $table) {
            $table->dropColumn('extended_fields');
        });
        Schema::table('device_categories', function (Blueprint $table) {
            $table->dropColumn('extended_fields');
        });
        Schema::table('device_tracks', function (Blueprint $table) {
            $table->dropColumn('extended_fields');
        });

        Schema::table('part_records', function (Blueprint $table) {
            $table->dropColumn('extended_fields');
        });
        Schema::table('part_categories', function (Blueprint $table) {
            $table->dropColumn('extended_fields');
        });
        Schema::table('part_tracks', function (Blueprint $table) {
            $table->dropColumn('extended_fields');
        });

        Schema::table('software_records', function (Blueprint $table) {
            $table->dropColumn('extended_fields');
        });
        Schema::table('software_categories', function (Blueprint $table) {
            $table->dropColumn('extended_fields');
        });
        Schema::table('software_tracks', function (Blueprint $table) {
            $table->dropColumn('extended_fields');
        });

        Schema::table('service_records', function (Blueprint $table) {
            $table->dropColumn('extended_fields');
        });
        Schema::table('service_tracks', function (Blueprint $table) {
            $table->dropColumn('extended_fields');
        });
        Schema::table('service_issues', function (Blueprint $table) {
            $table->dropColumn('extended_fields');
        });

        Schema::table('consumable_records', function (Blueprint $table) {
            $table->dropColumn('extended_fields');
        });
        Schema::table('consumable_categories', function (Blueprint $table) {
            $table->dropColumn('extended_fields');
        });
        Schema::table('consumable_tracks', function (Blueprint $table) {
            $table->dropColumn('extended_fields');
        });

        Schema::table('todo_records', function (Blueprint $table) {
            $table->dropColumn('extended_fields');
        });
        Schema::table('depreciation_rules', function (Blueprint $table) {
            $table->dropColumn('extended_fields');
        });
        Schema::table('purchased_channels', function (Blueprint $table) {
            $table->dropColumn('extended_fields');
        });
        Schema::table('vendor_records', function (Blueprint $table) {
            $table->dropColumn('extended_fields');
        });
        Schema::table('maintenance_records', function (Blueprint $table) {
            $table->dropColumn('extended_fields');
        });
        Schema::table('check_records', function (Blueprint $table) {
            $table->dropColumn('extended_fields');
        });
        Schema::table('check_tracks', function (Blueprint $table) {
            $table->dropColumn('extended_fields');
        });
        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn('extended_fields');
        });
    }
}
