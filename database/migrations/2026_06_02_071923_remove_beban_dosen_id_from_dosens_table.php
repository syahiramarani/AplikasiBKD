<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dosens', function (Blueprint $table) {

            $table->dropForeign(['beban_dosen_id']);

            $table->dropColumn('beban_dosen_id');

        });
    }

    public function down(): void
    {
        Schema::table('dosens', function (Blueprint $table) {

            $table->foreignId('beban_dosen_id')
                ->nullable()
                ->constrained('beban_dosens')
                ->nullOnDelete();

        });
    }
};
