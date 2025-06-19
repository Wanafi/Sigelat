<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_gelars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gelar_id')->constrained('gelars')->cascadeOnDelete();
            $table->foreignId('alat_id')->constrained('alats')->cascadeOnDelete();
            $table->enum('status_alat', ['Bagus', 'Rusak', 'Hilang']);
            $table->timestamps();
        });

        Schema::create('pelaksanas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gelar_id')->constrained('gelars')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('detail_alats', function (Blueprint $table) {
            $table->foreignId('mobil_id')->constrained('mobils')->cascadeOnDelete();
            $table->foreignId('alat_id')->constrained('alats')->cascadeOnDelete();
            $table->enum('kondisi', ['Bagus', 'Rusak', 'Hilang']);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Composite Primary Key (alat x mobil)
            $table->primary(['mobil_id', 'alat_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_alats');
        Schema::dropIfExists('pelaksanas');
        Schema::dropIfExists('detail_gelars');
    }
};
