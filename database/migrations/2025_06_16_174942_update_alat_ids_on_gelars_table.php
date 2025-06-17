<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('gelars', function (Blueprint $table) {
            $table->json('alat_ids')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('gelars', function (Blueprint $table) {
            // Rollback example (ubah sesuai kondisi awal kalau perlu)
            $table->string('alat_ids')->change();
        });
    }
};
