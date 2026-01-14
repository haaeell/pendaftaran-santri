<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('kategori_soal', function (Blueprint $table) {
            $table->enum('metode', ['pg', 'gmeet'])
                ->default('pg')
                ->after('nama_kategori');
        });
    }

    public function down(): void
    {
        Schema::table('kategori_soal', function (Blueprint $table) {
            $table->dropColumn('metode');
        });
    }
};
