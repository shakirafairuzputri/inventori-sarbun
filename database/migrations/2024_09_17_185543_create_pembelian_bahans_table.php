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
        Schema::create('pembelian_bahans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->unsignedBigInteger(column: 'bahan_id')->nullable();
            $table->float('pembelian')->nullable();
            $table->float('tambahan_sore')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // atau pegawai_id jika memakai pegawai
            $table->timestamps();

            $table->foreign('bahan_id')->references('id')->on('bahans')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_bahans');
    }
};
