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
            $table->foreignId('mobil_id')->constrained('mobils')->onDelete('cascade');
            $table->string('kode_barcode')->unique();
            $table->string('nama_alat');
            $table->string('kategori_alat');
            $table->string('merek_alat');
            $table->text('spesifikasi');
            $table->date('tanggal_masuk');
            $table->enum('status_alat', ['Baik', 'Rusak', 'Hilang']);
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alat');
    }
};
