<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('mata_kuliah', function (Blueprint $table) {
            // Hapus kolom bidang yang lama (varchar)
            $table->dropColumn('bidang');
            // Tambah kolom baru sebagai foreign key
            $table->foreignId('bidang_keahlian_id')->nullable()->constrained('bidang_keahlian');
        });
    }

    public function down()
    {
        Schema::table('mata_kuliah', function (Blueprint $table) {
            $table->dropForeign(['bidang_keahlian_id']);
            $table->dropColumn('bidang_keahlian_id');
            $table->string('bidang')->nullable();
        });
    }
};