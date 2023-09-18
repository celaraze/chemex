<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('device_tracks', function (Blueprint $table) {
            $table->string('description')->nullable()->after('user_id')->comment('归属原因');
            $table->string('deleted_description')->nullable()->after('description')->comment('解除归属原因');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device_tracks', function (Blueprint $table) {
            $table->dropColumn(['description', 'deleted_description']);
        });
    }
};
