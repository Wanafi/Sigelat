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
        Schema::create('detail_gelars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gelar_id')->constrained('gelars')->cascadeOnDelete();
            $table->foreignId('alat_id')->constrained('alats')->cascadeOnDelete();
            $table->enum('status_alat', ['Baik', 'Rusak', 'Hilang']);
            $table->text('keterangan')->nullable();
            $table->string('foto_kondisi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_gelar');
    }
};
