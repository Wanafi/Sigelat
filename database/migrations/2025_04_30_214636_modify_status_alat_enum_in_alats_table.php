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
        Schema::table('alats', function (Blueprint $table) {
            $table->enum('status_alat', ['Dipinjam', 'Rusak', 'Habis', 'Proses'])
                ->default('Dipinjam')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alats', function (Blueprint $table) {
            $table->enum('status_alat', ['Dipinjam', 'Rusak', 'Habis'])
                ->default('Dipinjam')
                ->change();
        });
    }
};
