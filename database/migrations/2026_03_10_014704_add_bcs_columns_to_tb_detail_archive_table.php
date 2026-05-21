<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBcsColumnsToTbDetailArchiveTable extends Migration
{
    public function up()
    {
        Schema::table('tb_detail_archive', function (Blueprint $table) {
            $table->string('npn')->nullable()->after('part_number');
            $table->string('unit')->nullable()->after('qty');
            $table->date('plan_date')->nullable()->after('unit');
            $table->integer('jarak_eksekusi')->nullable()->after('plan_date');
            $table->date('tanggal_bcs')->nullable()->after('jarak_eksekusi');
            $table->string('remarks')->nullable()->after('tanggal_bcs');
        });
    }

    public function down()
    {
        Schema::table('tb_detail_archive', function (Blueprint $table) {
            $table->dropColumn(['npn', 'unit', 'plan_date', 'jarak_eksekusi', 'tanggal_bcs', 'remarks']);
        });
    }
}