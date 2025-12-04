<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('hasil_tes_santri', function (Blueprint $table) {
            $table->json('jawaban')->nullable();
        });
    }

    public function down()
    {
        Schema::table('hasil_tes_santri', function (Blueprint $table) {
            $table->dropColumn('jawaban');
        });
    }
};
