<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAdminUsersTable extends Migration
{
    /**
     * Get the migration connection name.
     *
     * @return string|null
     */
    public function getConnection()
    {
        return $this->config('database.connection') ?: config('database.default');
    }

    public function config($key)
    {
        return config('admin.'.$key);
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->config('database.users_table'), function (Blueprint $table) {
            $table->integer('department_id');
            $table->char('gender')->default('无');
            $table->string('title')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->integer('ad_tag')->default(0);
            $table->string('extended_fields')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->config('database.users_table'), function (Blueprint $table) {
            $table->dropColumn('department_id');
            $table->dropColumn('gender');
            $table->dropColumn('title');
            $table->dropColumn('mobile');
            $table->dropColumn('email');
            $table->dropColumn('ad_tag');
            $table->dropColumn('extended_fields');
            $table->dropSoftDeletes();
        });
    }
}
