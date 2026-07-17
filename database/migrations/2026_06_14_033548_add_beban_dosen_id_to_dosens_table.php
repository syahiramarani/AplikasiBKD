<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('dosens', function (Blueprint $table) {

            $table->unsignedBigInteger('beban_dosen_id')
                ->nullable()
                ->after('jabatan');

            $table->foreign('beban_dosen_id')
                ->references('id')
                ->on('beban_dosens')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('dosens', function (Blueprint $table) {

            $table->dropForeign(['beban_dosen_id']);
            $table->dropColumn('beban_dosen_id');
        });
    }
};
