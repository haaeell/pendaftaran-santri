<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('data_diri_santri', function (Blueprint $table) {
            $table->foreignId('tahun_akademik_id')
                ->nullable()
                ->after('user_id')
                ->constrained('tahun_akademik')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('data_diri_santri', function (Blueprint $table) {
            $table->dropForeign(['tahun_akademik_id']);
            $table->dropColumn('tahun_akademik_id');
        });
    }
};
