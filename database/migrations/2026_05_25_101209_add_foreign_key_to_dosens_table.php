<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dosens', function (Blueprint $table) {

            $table->foreign('jurusan_id')
                ->references('id')
                ->on('jurusans')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('dosens', function (Blueprint $table) {

            $table->dropForeign(['jurusan_id']);
        });
    }
};