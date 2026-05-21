<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_planner', function (Blueprint $table) {
            $table->string('nama_planner')->nullable();
            $table->string('nrp')->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->string('departemen')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tb_planner', function (Blueprint $table) {
            $table->dropColumn(['nama_planner','nrp','email','phone','departemen','status','user_id']);
        });
    }
};