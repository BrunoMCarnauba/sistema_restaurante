<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CriaTabelaItensPedidos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itens_pedidos', function (Blueprint $table) {
            $table->bigIncrements('id_item_pedido');
            $table->unsignedBigInteger('pedido_id');
            $table->unsignedBigInteger('produto_id');
            $table->integer('quantidade')->unsigned()->default(1);
            $table->text('comentario')->nullable();
            $table->float('preco',7,2)->unsigned();
            $table->foreign('pedido_id')->references('id_pedido')->on('pedidos')->onDelete('cascade');
            $table->foreign('produto_id')->references('id_produto')->on('produtos');
            $table->boolean('status_pronto')->default(false);
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
        Schema::dropIfExists('itenspedidos');
    }
}
