<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyParamedisRawatInap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('paramedis_rawat_inap', function (Blueprint $table) {
            $table->foreign('id_transaksi_tindakan_medis_inap')->references('id')->on('transaksi_tindakan_medis_inap')->onDelete('cascade');
            $table->foreign('id_dokter')->references('id')->on('dokter')->onDelete('cascade');
            $table->foreign('id_pasien')->references('id')->on('pasien')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
