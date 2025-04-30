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
        Schema::table('mobils', function (Blueprint $table) {
            $table->enum('status_mobil', ['Aktif', 'TidakAktif', 'DalamPerbaikan', 'ProsesPelaporan'])
                ->default('Aktif')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mobils', function (Blueprint $table) {
            $table->enum('status_mobil', ['Aktif', 'TidakAktif', 'DalamPerbaikan'])
                ->default('Aktif')
                ->change();
        });
    }
};
