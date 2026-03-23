<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection(config('activitylog.database_connection'))->table(config('activitylog.table_name'), function (Blueprint $table) {
            $table->string('session_id')->nullable()->after('properties');
            $table->string('ip_address', 45)->nullable()->after('session_id');

            $table->index('session_id');
            $table->index('ip_address');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection(config('activitylog.database_connection'))->table(config('activitylog.table_name'), function (Blueprint $table) {
            $table->dropIndex(['session_id']);
            $table->dropIndex(['ip_address']);
            $table->dropIndex(['created_at']);
            $table->dropColumn(['session_id', 'ip_address']);
        });
    }
};
