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
        Schema::create('laporans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alat_id')->constrained('alats')->onDelete('cascade');
            $table->foreignId('mobil_id')->constrained('mobils')->onDelete('cascade');
            $table->datetime(column: 'tanggal_lapor');
            $table->string(column: 'pelapor');
            $table->text(column: 'deskripsi_kerusakan');
            $table->enum(column: 'status_tindaklanjut', allowed: ['diajukan', 'diproses','selesai'])->options;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporans');
    }
};
