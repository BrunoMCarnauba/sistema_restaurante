<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function() { return redirect()->route('login'); });

Route::get('/login', 'LoginController@index')->name('login');
Route::post('/logar', 'LoginController@logar')->name('logar');
Route::get('/logout', 'LoginController@logout')->name('logout');

Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::group(['prefix' => 'usuarios'], function () {
        Route::get('/', 'FuncionariosController@index')->name('funcionarios.listar');
        Route::get('/novo', 'FuncionariosController@novo')->name('funcionarios.novo');
        Route::post('/cadastrar', 'FuncionariosController@cadastrar')->name('funcionarios.cadastrar');
        Route::get('/edicao/{id}', 'FuncionariosController@edicao')->name('funcionarios.edicao');
        Route::post('/editar/{id}', 'FuncionariosController@editar')->name('funcionarios.editar');
        Route::get('/excluir/{id?}', 'FuncionariosController@excluir')->name('funcionarios.excluir');
    });

    Route::group(['prefix' => 'pedidos'], function () {
        Route::get('/', 'PedidosController@index')->name('pedidos.listar');
        Route::get('/excluir/{id?}', 'PedidosController@excluir')->name('pedidos.excluir');
        Route::get('/{idMesa}/item/{idItem}/mudarStatus', 'ItemPedidoController@mudarStatus')->name('itens_pedido.mudarStatus');
    });
    
    Route::group(['prefix' => 'mesas'], function () {
        Route::get('/', 'MesaController@index')->name('mesas.listar');
        Route::get('/excluir/{id?}', 'MesaController@excluir')->name('mesas.excluir');
        Route::get('/visualizar/{id}', 'ItemPedidoController@visualizarItens')->name('itens_pedido.visualizar');
        Route::get('/{idMesa}/item/excluir/{idItem?}', 'ItemPedidoController@excluir')->name('itens_pedido.excluir');
    });

});

