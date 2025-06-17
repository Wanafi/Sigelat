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
            $table->string('status')->default('Proses')->after('riwayatable_type');
        });
    }

    public function down(): void
    {
        Schema::table('riwayats', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
