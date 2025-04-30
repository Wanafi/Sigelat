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
        Schema::create('alats', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barcode')->unique();
            $table->string('nama_alat');
            $table->string('kategori_alat');
            $table->string('merek_alat');
            $table->text('spesifikasi');
            $table->date('tanggal_pembelian');
            $table->enum('status_alat', ['Dipinjam', 'Rusak', 'Habis']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alats');
    }
};
