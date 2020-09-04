<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', 'Api\FuncionariosController@logar');
Route::get('/categoriasprodutos/', 'Api\CategoriasProdutosController@listar');
Route::get('/produtos/', 'Api\ProdutosController@listar');

Route::group(['middleware' => ['jwt']], function () {   //O JWTMiddleware só permite o acesso as rotas que estão agrupadas nesse Middleware, se passar o token válido que foi gerado no momento do login
    Route::group(['prefix' => 'funcionarios'], function () {
        Route::post('/', 'Api\FuncionariosController@cadastrar');
        Route::get('/', 'Api\FuncionariosController@listar');
        Route::get('/{id}', 'Api\FuncionariosController@buscar');
        Route::put('/{id}', 'Api\FuncionariosController@atualizar');
        Route::delete('/{id}', 'Api\FuncionariosController@remover');
    });
    
    Route::group(['prefix' => 'produtos'], function () {
        Route::post('/', 'Api\ProdutosController@cadastrar');
//        Route::get('/', 'Api\ProdutosController@listar'); //Coloquei fora do middleware jwt para poder ser acessado sem precisar de login
        Route::get('/{id}', 'Api\ProdutosController@buscar');
        Route::put('/{id}', 'Api\ProdutosController@atualizar');
        Route::delete('/{id}', 'Api\ProdutosController@remover');
    });
    
    Route::group(['prefix' => 'mesas'], function () {
        Route::post('/', 'Api\MesasController@cadastrar');
        Route::get('/', 'Api\MesasController@listar');
        Route::get('/{id}', 'Api\MesasController@buscar');
        Route::put('/{id}', 'Api\MesasController@atualizar');
        Route::delete('/{id}', 'Api\MesasController@remover');
    });
    
    Route::group(['prefix' => 'pedidos'], function () {
        Route::post('/', 'Api\PedidosController@cadastrar');
        Route::get('/', 'Api\PedidosController@listar');
        Route::get('/{id}', 'Api\PedidosController@buscar');
        Route::put('/{id}', 'Api\PedidosController@atualizar');
        Route::delete('/{id}', 'Api\PedidosController@remover');
    });
    
    Route::group(['prefix' => 'itenspedidos'], function () {
        Route::post('/', 'Api\ItensPedidosController@cadastrar');
        Route::get('/listar/{idPedido}', 'Api\ItensPedidosController@listar');
        Route::get('/{id}', 'Api\ItensPedidosController@buscar');
        Route::put('/{id}', 'Api\ItensPedidosController@atualizar');
        Route::delete('/{id}', 'Api\ItensPedidosController@remover');
    });
    
    Route::group(['prefix' => 'cargos'], function () {
        Route::post('/', 'Api\CargosController@cadastrar');
        Route::get('/', 'Api\CargosController@listar');
        Route::get('/{id}', 'Api\CargosController@buscar');
        Route::put('/{id}', 'Api\CargosController@atualizar');
        Route::delete('/{id}', 'Api\CargosController@remover');
    });
    
    Route::group(['prefix' => 'categoriasprodutos'], function () {
        Route::post('/', 'Api\CategoriasProdutosController@cadastrar');
//        Route::get('/', 'Api\CategoriasProdutosController@listar');
        Route::get('/{id}', 'Api\CategoriasProdutosController@buscar');
        Route::put('/{id}', 'Api\CategoriasProdutosController@atualizar');
        Route::delete('/{id}', 'Api\CategoriasProdutosController@remover');
    });
});