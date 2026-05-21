<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_reconsult', function (Blueprint $table) {
            $table->foreignId('backlog_id')->nullable()->change();
            $table->text('masalah')->nullable();
            $table->text('solusi')->nullable();
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open')->change();
            $table->date('tanggal_konsultasi')->nullable();
            $table->foreignId('pic_vendor')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('pic_planner')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('resolved_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tb_reconsult', function (Blueprint $table) {
            $table->dropColumn(['masalah','solusi','priority','tanggal_konsultasi','pic_vendor','pic_planner','resolved_date']);
        });
    }
};