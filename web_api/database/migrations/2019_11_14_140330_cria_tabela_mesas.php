<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CriaTabelaMesas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mesas', function (Blueprint $table) {
            $table->bigIncrements('id_mesa');
            $table->unsignedBigInteger('pedido_id')->nullable();
            $table->string('status')->default('Disponível');
            $table->foreign('pedido_id')->references('id_pedido')->on('pedidos')->onDelete('set null'); //Ao deletar um pedido, essa coluna fica igual a null. - https://stackoverflow.com/questions/20869072/laravel-schema-ondelete-set-null
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
        Schema::dropIfExists('mesas');
    }
}
