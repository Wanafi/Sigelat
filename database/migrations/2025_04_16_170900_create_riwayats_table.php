<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('riwayats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('riwayatable_id');
            $table->string('riwayatable_type');
            $table->unsignedBigInteger('user_id')->nullable(); // relasi ke users
            $table->date('tanggal_cek')->nullable();
            $table->string('aksi')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayats');
    }
};
