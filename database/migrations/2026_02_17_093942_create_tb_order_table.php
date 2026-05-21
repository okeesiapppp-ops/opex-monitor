<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_order', function (Blueprint $table) {
            $table->id();
            $table->foreignId('backlog_id')->constrained('tb_backlog')->cascadeOnDelete();
            $table->foreignId('vendor_id')->nullable()->constrained('tb_vendor')->nullOnDelete();
            $table->foreignId('planner_id')->nullable()->constrained('tb_planner')->nullOnDelete();
            $table->string('no_order')->nullable();
            $table->date('tanggal')->nullable();
            $table->enum('status', ['pending', 'process', 'done'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->integer('qty')->default(0);
            $table->string('satuan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_order');
    }
};