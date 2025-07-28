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
    Schema::table('detail_gelars', function (Blueprint $table) {
        $table->string('foto_alat')->nullable()->after('status_alat');
    });
}

public function down(): void
{
    Schema::table('detail_gelar', function (Blueprint $table) {
        $table->dropColumn('foto_alat');
    });
}

};
