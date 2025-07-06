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
            $table->enum('status_alat', ['Baik', 'Rusak', 'Hilang']);
            $table->timestamps();
        });

        Schema::create('pelaksanas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gelar_id')->constrained('gelars')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ✅ kolom + FK
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelaksanas');
        Schema::dropIfExists('detail_gelars');
    }
};
