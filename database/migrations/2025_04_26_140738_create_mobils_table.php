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
        Schema::create('mobils', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_plat');
            $table->string('merk_mobil');
            $table->string('no_unit');
            $table->string('nama_tim')->nullable();
            $table->Enum ('status_mobil', ['Aktif','Tidak Aktif','Dalam Perbaikan']);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobils');
    }
};
