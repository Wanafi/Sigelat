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
        Schema::table('gelars', function (Blueprint $table) {
            $table->text('pelaksana')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('gelars', function (Blueprint $table) {
            $table->dropColumn('pelaksana');
        });
    }
};
