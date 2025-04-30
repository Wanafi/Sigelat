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
            // Menghapus kolom yang lama
            $table->dropForeign(['mobil_id']);
            $table->dropForeign(['alat_id']);
            $table->dropColumn('mobil_id');
            $table->dropColumn('alat_id');

            // Menambahkan kolom polymorphic
            $table->unsignedBigInteger('riwayatable_id');
            $table->string('riwayatable_type');

            // Menambahkan indeks untuk kolom polymorphic
            $table->index(['riwayatable_id', 'riwayatable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riwayats', function (Blueprint $table) {
            // Menambahkan kembali kolom mobil_id dan alat_id
            $table->unsignedBigInteger('mobil_id');
            $table->unsignedBigInteger('alat_id');

            // Menambahkan kembali foreign key untuk mobil_id dan alat_id
            $table->foreign('mobil_id')->references('id')->on('mobils')->onDelete('cascade');
            $table->foreign('alat_id')->references('id')->on('alats')->onDelete('cascade');

            // Menghapus kolom polymorphic
            $table->dropColumn('riwayatable_id');
            $table->dropColumn('riwayatable_type');
        });
    }
};