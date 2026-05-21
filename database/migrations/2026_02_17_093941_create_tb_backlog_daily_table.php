<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_backlog_daily', function (Blueprint $table) {
            $table->id();
            $table->foreignId('backlog_id')->constrained('tb_backlog')->cascadeOnDelete();
            $table->foreignId('vendor_id')->nullable()->constrained('tb_vendor')->nullOnDelete();
            $table->foreignId('planner_id')->nullable()->constrained('tb_planner')->nullOnDelete();
            $table->date('tanggal');
            $table->boolean('is_ready')->default(false);
            $table->text('keterangan')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_backlog_daily');
    }
};