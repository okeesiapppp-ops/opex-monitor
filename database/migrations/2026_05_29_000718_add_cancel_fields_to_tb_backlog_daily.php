<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_backlog_daily', function (Blueprint $table) {
            $table->string('cancel_reason')->nullable()->after('posisi');
            $table->string('previous_status')->nullable()->after('cancel_reason');
        });
    }

    public function down(): void
    {
        Schema::table('tb_backlog_daily', function (Blueprint $table) {
            $table->dropColumn(['cancel_reason', 'previous_status']);
        });
    }
};
