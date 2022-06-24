<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Version302 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('column_sorts', function (Blueprint $table) {
            if (Schema::hasColumn('column_sorts', 'field')) {
                $table->renameColumn('field', 'name');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('column_sorts', function (Blueprint $table) {
            if (Schema::hasColumn('column_sorts', 'name')) {
                $table->renameColumn('name', 'field');
            }
        });
    }
}
