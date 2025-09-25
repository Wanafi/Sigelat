<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('riwayats', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('alat_id')->nullable();
    $table->morphs('riwayatable');
    $table->unsignedBigInteger('user_id')->nullable(); // ✅ WAJIB ADA INI DULU
    $table->date('tanggal_cek')->nullable();
    $table->string('status')->default('Proses');
    $table->string('aksi')->nullable();
    $table->text('catatan')->nullable();
    $table->timestamps();

    $table->foreign('alat_id')->references('id')->on('alats')->nullOnDelete();
    $table->foreign('user_id')->references('id')->on('users')->nullOnDelete(); // ✅ Baru aman
});

    }

    public function down(): void
    {
        Schema::dropIfExists('riwayats');
    }
};
