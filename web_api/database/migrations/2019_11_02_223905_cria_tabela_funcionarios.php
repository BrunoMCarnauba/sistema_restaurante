<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CriaTabelaFuncionarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->bigIncrements('id_funcionario');
            $table->string('nome');
            $table->string('email');
            $table->string('senha');
            $table->unsignedBigInteger('cargo_id'); //chave estrangeira precisar ser unsigned: https://stackoverflow.com/questions/47728909/laravel-migration-errno-150-foreign-key-constraint-is-incorrectly-formed
            $table->foreign('cargo_id')->references('id_cargo')->on('cargos');
//            $table->softDeletes();
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
        Schema::dropIfExists('funcionarios');
    }
}
