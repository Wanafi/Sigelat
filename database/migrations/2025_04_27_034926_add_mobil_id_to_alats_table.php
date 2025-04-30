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
            $table->unsignedBigInteger('mobil_id')->nullable();

            // Optional: Definisikan foreign key constraint jika perlu
            $table->foreign('mobil_id')->references('id')->on('mobils')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alats', function (Blueprint $table) {
            $table->dropForeign(['mobil_id']);
            $table->dropColumn('mobil_id');
        });
    }
};
