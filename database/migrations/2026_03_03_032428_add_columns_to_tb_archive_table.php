<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_archive', function (Blueprint $table) {
            $table->string('periode')->nullable()->after('id');
            $table->date('tanggal_archive')->nullable()->after('periode');
            $table->integer('total_backlog')->default(0)->after('tanggal_archive');
            $table->integer('total_complete')->default(0)->after('total_backlog');
            $table->integer('total_pending')->default(0)->after('total_complete');
            $table->integer('total_outstanding')->default(0)->after('total_pending');
            $table->decimal('achievement_percentage', 5, 2)->default(0)->after('total_outstanding');
            $table->text('notes')->nullable()->after('achievement_percentage');
            $table->unsignedBigInteger('created_by')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('tb_archive', function (Blueprint $table) {
            $table->dropColumn([
                'periode',
                'tanggal_archive',
                'total_backlog',
                'total_complete',
                'total_pending',
                'total_outstanding',
                'achievement_percentage',
                'notes',
                'created_by',
            ]);
        });
    }
};