<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_archive', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_archive');
            $table->string('periode', 20);
            $table->integer('total_backlog')->default(0);
            $table->integer('total_complete')->default(0);
            $table->integer('total_pending')->default(0);
            $table->integer('total_cancelled')->default(0);
            $table->decimal('achievement_percentage', 5, 2)->default(0);
            $table->decimal('backlog_ratio', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('tb_detail_archive', function (Blueprint $table) {
            $table->id();
            $table->foreignId('archive_id')->constrained('tb_archive')->cascadeOnDelete();
            $table->foreignId('backlog_id')->nullable()->constrained('tb_backlog')->nullOnDelete();
            $table->string('unit_number', 50)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('part_number', 100)->nullable();
            $table->string('part_name', 255)->nullable();
            $table->integer('qty')->default(0);
            $table->string('status', 50)->nullable();
            $table->date('completion_date')->nullable();
            $table->string('vendor', 100)->nullable();
            $table->integer('days_to_complete')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_detail_archive');
        Schema::dropIfExists('tb_archive');
    }
};