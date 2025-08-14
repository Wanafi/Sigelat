<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeStatusColumnInRiwayatsTable extends Migration
{
    public function up(): void
    {
        Schema::table('riwayats', function (Blueprint $table) {
            // Ubah kolom status menjadi ENUM
            $table->enum('status', ['Proses', 'Selesai'])
                ->default('Proses')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('riwayats', function (Blueprint $table) {
            // Kembalikan ke tipe string (jika sebelumnya string)
            $table->string('status')->default('Proses')->change();
        });
    }
}
