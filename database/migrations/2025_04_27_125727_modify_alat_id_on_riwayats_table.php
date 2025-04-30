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
        Schema::table('riwayats', function (Blueprint $table) {
            $table->unsignedBigInteger('alat_id')->nullable()->change(); // Membuat alat_id nullable
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayats', function (Blueprint $table) {
            $table->unsignedBigInteger('alat_id')->nullable(false)->change(); // Mengubah kembali alat_id menjadi non-nullable
        });
    }
};
