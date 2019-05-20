<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConflitosMovimentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('movimentos', function (Blueprint $table) {
            $table->enum('tipo_conflito', ['S', 'B'])->nullable();
            $table->text('justificacao_conflito')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('movimentos', function (Blueprint $table) {
            $table->dropColumn(['tipo_conflito', 'justificacao_conflito']);
        });
    }
}
