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
        Schema::create('bahans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->unsignedBigInteger('kategori_id'); // Foreign key ke tabel kategori_bhns
            $table->string('satuan');
            $table->float('stok')->default(0);
            $table->timestamps();
            $table->engine = 'InnoDB';

            // Menambahkan foreign key constraint
            $table->foreign('kategori_id')->references('id')->on('kategori_bhns')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahans');
    }
};
