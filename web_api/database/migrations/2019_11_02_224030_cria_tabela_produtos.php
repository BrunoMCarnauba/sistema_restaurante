<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CriaTabelaProdutos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->bigIncrements('id_produto');
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->unsignedBigInteger('categoria_id');
            $table->integer('calorias')->nullable()->unsigned();    //unsigned() não deixa o valor ser negativo
            $table->float('preco',6,2)->unsigned();
            $table->foreign('categoria_id')->references('id_categoria_produto')->on('categorias_produtos');
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
        Schema::dropIfExists('produtos');
    }
}
