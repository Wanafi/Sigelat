<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('detail_alats')) {
            $columns = DB::select("SHOW COLUMNS FROM detail_alats");

            $hasGelarId = collect($columns)->pluck('Field')->contains('gelar_id');

            if ($hasGelarId) {
                try {
                    DB::statement("ALTER TABLE detail_alats DROP FOREIGN KEY detail_alats_gelar_id_foreign");
                } catch (\Throwable $e) {
                    // Abaikan jika FK sudah tidak ada
                }

                try {
                    DB::statement("ALTER TABLE detail_alats DROP COLUMN gelar_id");
                } catch (\Throwable $e) {
                    // Abaikan jika column sudah tidak ada
                }
            }
        }
    }

    public function down(): void
    {
        Schema::table('detail_alats', function ($table) {
            $table->unsignedBigInteger('gelar_id')->nullable();
            $table->foreign('gelar_id')->references('id')->on('gelars')->onDelete('cascade');
        });
    }
};
