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
        Schema::create('retur_bahans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->unsignedBigInteger(column: 'bahan_id')->nullable();
            $table->float('retur_baik')->nullable();
            $table->float('retur_rusak')->nullable();
            $table->enum('jenis_kerusakan', ['Kadaluarsa', 'Rusak'])->default('Kadaluarsa');
            $table->enum('status', ['Sudah Dikembalikan', 'Sudah Diganti'])->default('Sudah Dikembalikan');

            $table->unsignedBigInteger('user_id')->nullable(); // atau pegawai_id jika memakai pegawai
            $table->timestamps();

            $table->foreign('bahan_id')->references('id')->on('bahans')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // sesuaikan dengan tabel pegawai jika ada
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retur_bahans');
    }
};
