<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('tb_backlog_daily', function (Blueprint $table) {
            $table->decimal('priority_score', 8, 4)->nullable()->after('status');
            $table->unsignedInteger('priority_rank')->nullable()->after('priority_score');
        });
    }
    public function down(): void {
        Schema::table('tb_backlog_daily', function (Blueprint $table) {
            $table->dropColumn(['priority_score', 'priority_rank']);
        });
    }
};
