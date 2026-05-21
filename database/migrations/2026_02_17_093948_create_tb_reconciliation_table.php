<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_reconciliation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('backlog_id')->constrained('tb_backlog')->cascadeOnDelete();
            $table->date('tanggal')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status')->nullable();
            $table->integer('qty_order')->default(0);
            $table->integer('qty_terima')->default(0);
            $table->string('no_dokumen')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_reconciliation');
    }
};