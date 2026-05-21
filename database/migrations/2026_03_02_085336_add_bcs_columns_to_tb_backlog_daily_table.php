<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_backlog_daily', function (Blueprint $table) {
            $table->string('reservation_number')->nullable();  // CJ - Reservation
            $table->string('reservation_item')->nullable();    // CK - Rsv Item
            $table->string('mo_description')->nullable();      // CC - backlog desc
            $table->string('equipment')->nullable();           // CN - Equipment
            $table->string('egi')->nullable();                 // P  - EGI
            $table->string('part_number')->nullable();         // BY - PN
            $table->string('npn')->nullable();                 // BZ - NPN
            $table->string('manufacture')->nullable();         // Mnemonic
            $table->integer('qty')->default(1);                // CA - qty
            $table->string('unit')->nullable();                // CB - Unit
            $table->date('plan_date')->nullable();             // CE - plan date
            $table->string('jarak_eksekusi')->nullable();      // CF - Jarak Eksekusi
            $table->date('tanggal_bcs')->nullable();           // CH - TANGGAL BCS
            $table->text('remarks')->nullable();               // DO - REMARKS 1
        });
    }

    public function down(): void
    {
        Schema::table('tb_backlog_daily', function (Blueprint $table) {
            $table->dropColumn([
                'reservation_number', 'reservation_item', 'mo_description',
                'equipment', 'egi', 'part_number', 'npn', 'manufacture',
                'qty', 'unit', 'plan_date', 'jarak_eksekusi',
                'tanggal_bcs', 'remarks'
            ]);
        });
    }
};