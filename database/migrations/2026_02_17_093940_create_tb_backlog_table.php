<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_backlog', function (Blueprint $table) {
            $table->id();
            $table->string('unit_number')->nullable();
            $table->string('model')->nullable();
            $table->string('part_number')->nullable();
            $table->string('part_name')->nullable();
            $table->integer('qty')->default(0);
            $table->string('satuan')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'complete', 'cancelled'])->default('pending');
            $table->string('vendor')->nullable();
            $table->date('eta')->nullable();
            $table->string('location')->nullable();
            $table->string('ref_penarikan')->nullable();
            $table->enum('stock_status', ['available', 'on_order', 'backorder'])->nullable();
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
            $table->date('completion_date')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_backlog');
    }
};