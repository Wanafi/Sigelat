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
        $table->dropForeign(['mobil_id']);
        $table->dropColumn('mobil_id');
    });
}

public function down(): void
{
    Schema::table('alats', function (Blueprint $table) {
        $table->foreignId('mobil_id')->nullable()->constrained('mobils')->nullOnDelete();
    });
}

};
