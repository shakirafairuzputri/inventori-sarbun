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
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_brg');
            $table->string('kelompok');
            $table->unsignedBigInteger('kategori_brg_id');
            $table->unsignedBigInteger('satuan_brg_id');
            $table->float('stok_brg');
            $table->timestamps();

            $table->foreign('kategori_brg_id')->references('id')->on('kategori_brgs')->onDelete('cascade');
            $table->foreign('satuan_brg_id')->references('id')->on('satuans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
