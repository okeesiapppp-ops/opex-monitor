<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_detail_archive', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('archive_id');
            $table->string('reservation_number')->nullable();
            $table->string('reservation_item')->nullable();
            $table->string('mo_description')->nullable();
            $table->string('equipment')->nullable();
            $table->string('egi')->nullable();
            $table->string('part_number')->nullable();
            $table->string('manufacture')->nullable();
            $table->integer('qty')->default(1);
            $table->string('status')->default('complete');
            $table->date('tanggal')->nullable();
            $table->timestamps();

            $table->foreign('archive_id')
                ->references('id')
                ->on('tb_archive')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_detail_archive');
    }
};