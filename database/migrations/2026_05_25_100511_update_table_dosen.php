<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dosens', function (Blueprint $table) {

            // rename kolom
            $table->renameColumn('nama', 'nama_dosen');

            // tambah kolom baru
            $table->string('nip')->after('nama_dosen');
            $table->string('jabatan')->nullable()->after('status');
            $table->string('keahlian')->nullable()->after('jabatan');

            // ubah enum status
            $table->enum('status', ['DT', 'DS'])->change();

            // hapus kolom lama yang tidak dipakai
            $table->dropColumn([
                'nidn',
                'Prodi_id',
                'sks',
                'jam'
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('dosens', function (Blueprint $table) {

            $table->renameColumn('nama_dosen', 'nama');

            $table->string('nidn')->nullable();
            $table->bigInteger('Prodi_id')->nullable();
            $table->integer('sks')->nullable();
            $table->integer('jam')->nullable();

            $table->dropColumn([
                'nip',
                'jabatan',
                'keahlian'
            ]);

            $table->enum('status', ['tetap', 'tidak_tetap'])->change();
        });
    }
};