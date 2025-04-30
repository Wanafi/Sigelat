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
        Schema::create('riwayats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alat_id')->constrained('alats')->onDelete('cascade');
            $table->foreignId('mobil_id')->constrained('mobils')->onDelete('cascade');
            $table->datetime('tanggal_keluar');
            $table->datetime('tanggal_kembali');
            $table->enum('status_penggunaan', ['Digunakan', 'Kembali', 'Hilang', 'Rusak']);
            $table->string('pengguna');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayats');
    }
};
