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
            $table->Enum ('merk_mobil', ['Hilux','Innova','Carry']);
            $table->Enum ('no_unit', ['Unit12','Unit13','Unit14']);
            $table->Enum ('status_mobil', ['Aktif','Tidak Aktif','Dalam Perbaikan', 'Proses']);
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
