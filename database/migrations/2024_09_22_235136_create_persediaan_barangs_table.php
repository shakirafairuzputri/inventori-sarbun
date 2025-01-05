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
        Schema::create('persediaan_barangs', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->unsignedBigInteger(column: 'barang_id')->nullable();
            // $table->string('kategori_brg'); 
            // $table->string('satuan_brg');
            $table->float('stok_awal')->nullable();
            $table->float('tambah')->nullable();
            $table->float('kurang')->nullable();
            $table->float('sisa')->nullable();
            $table->unsignedBigInteger('pegawai_brgm')->nullable(); // Foreign key untuk barang masuk
            $table->unsignedBigInteger('pegawai_brgk')->nullable(); // Foreign key untuk barang keluar
            $table->timestamps();

            $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('cascade');
            $table->foreign('pegawai_brgm')->references('id')->on('users')->onDelete('set null');
            $table->foreign('pegawai_brgk')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persediaan_barangs');
    }
};
