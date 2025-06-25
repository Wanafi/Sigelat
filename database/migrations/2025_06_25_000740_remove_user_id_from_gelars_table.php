<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('gelars', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Jika sebelumnya ada foreign key
            $table->dropColumn('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('gelars', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();

            // Tambahkan kembali foreign key jika diperlukan
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }
};
