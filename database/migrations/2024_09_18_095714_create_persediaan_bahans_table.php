<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('persediaan_bahans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->unsignedBigInteger(column: 'produksi_id')->nullable();
            $table->float('stok_awal')->nullable();
            $table->float('stok_siang')->nullable();
            $table->float('cek_fisik')->nullable();
            $table->float('selisih')->nullable();
            $table->float('stok_akhir')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();

            // $table->foreign('kategori_id')->references('id')->on(table: 'kategori_bhns')->onDelete('cascade');
            $table->foreign('produksi_id')->references('id')->on('produksi_bahans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persediaan_bahans');
    }
};
