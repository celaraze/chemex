<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('import_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('item');
            $table->integer('succeed')->default(0);
            $table->integer('failed')->default(0);
            $table->integer('operator');
            $table->timestamps();
        });

        Schema::create('import_log_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('log_id');
            $table->integer('status')->default(0);
            $table->string('log');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::drop('import_logs');
        Schema::drop('import_log_details');
    }
};
