<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('gelars', function (Blueprint $table) {
            $table->text('keterangan')->nullable()->after('tanggal');
        });

        Schema::table('mobils', function (Blueprint $table) {
            $table->string('no_seri')->nullable()->after('nama_mobil');
        });
    }

    public function down(): void
    {
        Schema::table('gelars', function (Blueprint $table) {
            $table->dropColumn('keterangan');
        });

        Schema::table('mobils', function (Blueprint $table) {
            $table->dropColumn('no_seri');
        });
    }
};
