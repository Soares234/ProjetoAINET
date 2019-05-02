<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInitialTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipos_licencas', function (Blueprint $table) {
            $table->string('code', 20)->primary();
            $table->string('nome');
        });

        Schema::create('classes_certificados', function (Blueprint $table) {
            $table->string('code', 20)->primary();
            $table->string('nome');
        });

        Schema::create('aerodromos', function (Blueprint $table) {
            $table->string('code', 20)->primary();
            $table->string('nome');
            $table->string('militar')->default(false);
            $table->string('ultraleve')->default(false);
            $table->softDeletes();
        });

        // tabela de users usada também para representar sócios e pilotos
        // SOCIOS & PILOTOS
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // CAMPOS RELATIVOS AO SÓCIO (Todos users são sócios)
            $table->integer('num_socio');
            $table->string('nome_informal', 40);
            $table->enum('sexo', ['M', 'F']);
            $table->date('data_nascimento');
            $table->string('nif', 9)->nullable();
            $table->string('telefone', 20)->nullable();
            $table->text('endereco')->nullable();
            $table->enum('tipo_socio', ['P', 'NP', 'A']);
            $table->boolean('quota_paga')->default(true);
            $table->boolean('ativo')->default(true);
            $table->boolean('password_inicial')->default(true);
            $table->boolean('direcao')->default(false);
            $table->string('foto_url')->nullable();

            // CAMPOS RELATIVOS AO PILOTO (opcionais - só para pilotos)
            $table->string('num_licenca', 30)->nullable();
            $table->string('tipo_licenca', 20)->nullable();
            $table->foreign('tipo_licenca')->references('code')->on('tipos_licencas');
            $table->boolean('instrutor')->nullable();
            $table->boolean('aluno')->nullable();
            $table->date('validade_licenca')->nullable();
            $table->boolean('licenca_confirmada')->nullable();
            $table->string('num_certificado', 30)->nullable();
            $table->string('classe_certificado', 20)->nullable();
            $table->foreign('classe_certificado')->references('code')->on('classes_certificados');
            $table->date('validade_certificado')->nullable();
            $table->boolean('certificado_confirmado')->nullable();
        });

        Schema::create('aeronaves', function (Blueprint $table) {
            $table->string('matricula', 8)->primary();
            $table->string('marca', 40);
            $table->string('modelo', 40);
            $table->integer('num_lugares');
            $table->integer('conta_horas');
            $table->decimal('preco_hora', 13, 2);
            $table->timestamps();
            $table->softDeletes();
        });

        // aeronaves_users - definem quem está apto a voar uma determinada aeronave
        Schema::create('aeronaves_pilotos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('matricula', 8)->index();
            $table->unsignedBigInteger('piloto_id');
            $table->unique(['matricula', 'piloto_id']);
            $table->foreign('matricula')->references('matricula')->on('aeronaves')->onDelete('cascade');
            $table->foreign('piloto_id')->references('id')->on('users')->onDelete('cascade');
        });

        // aeronaves_valores - tabela que mapeia unidades do conta-horas de uma aeronave
        // em valores de tempo e preço
        Schema::create('aeronaves_valores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('matricula', 8);
            $table->integer('unidade_conta_horas');
            $table->integer('minutos');
            $table->decimal('preco', 13, 2);
            $table->unique(['matricula', 'unidade_conta_horas']);
            $table->foreign('matricula')->references('matricula')->on('aeronaves')->onDelete('cascade');
        });

        Schema::create('movimentos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('data');
            $table->datetime('hora_descolagem');
            $table->datetime('hora_aterragem');
            $table->string('aeronave', 8);
            $table->foreign('aeronave')->references('matricula')->on('aeronaves');
            $table->integer('num_diario');
            $table->integer('num_servico');
            $table->unsignedBigInteger('piloto_id');
            $table->foreign('piloto_id')->references('id')->on('users');
            $table->string('num_licenca_piloto', 30);
            $table->date('validade_licenca_piloto');
            $table->string('tipo_licenca_piloto', 20);
            $table->foreign('tipo_licenca_piloto')->references('code')->on('tipos_licencas');
            $table->string('num_certificado_piloto', 30);
            $table->date('validade_certificado_piloto');
            $table->string('classe_certificado_piloto', 20);
            $table->foreign('classe_certificado_piloto')->references('code')->on('classes_certificados');
            $table->enum('natureza', ['T', 'I', 'E']);
            $table->string('aerodromo_partida', 20);
            $table->foreign('aerodromo_partida')->references('code')->on('aerodromos');
            $table->string('aerodromo_chegada', 20);
            $table->foreign('aerodromo_chegada')->references('code')->on('aerodromos');
            $table->integer('num_aterragens');
            $table->integer('num_descolagens');
            $table->integer('num_pessoas');
            $table->integer('conta_horas_inicio');
            $table->integer('conta_horas_fim');
            $table->integer('tempo_voo');
            $table->decimal('preco_voo', 13, 2);
            $table->enum('modo_pagamento', ['N', 'M', 'T', 'P']);
            $table->string('num_recibo', 20);
            $table->text('observacoes')->nullable();
            $table->boolean('confirmado');

            // Opcionais - só se voo de instrução (Natureza = I):
            $table->enum('tipo_instrucao', ['D', 'S'])->nullable();
            $table->unsignedBigInteger('instrutor_id')->nullable();
            $table->foreign('instrutor_id')->references('id')->on('users')->nullable();
            $table->string('num_licenca_instrutor', 30)->nullable();
            $table->date('validade_licenca_instrutor')->nullable();
            $table->string('tipo_licenca_instrutor', 20)->nullable();
            $table->foreign('tipo_licenca_instrutor')->references('code')->on('tipos_licencas');
            $table->string('num_certificado_instrutor', 30)->nullable();
            $table->date('validade_certificado_instrutor')->nullable();
            $table->string('classe_certificado_instrutor', 20)->nullable();
            $table->foreign('classe_certificado_instrutor')->references('code')->on('classes_certificados');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movimentos');
        Schema::dropIfExists('aeronaves_valores');
        Schema::dropIfExists('aeronaves_pilotos');
        Schema::dropIfExists('aeronaves');
        Schema::dropIfExists('users');
        Schema::dropIfExists('aerodromos');
        Schema::dropIfExists('classes_certificados');
        Schema::dropIfExists('tipos_licencas');
    }
}
