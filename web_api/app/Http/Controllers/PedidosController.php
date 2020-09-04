<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\ItemPedido;
use Illuminate\Pagination\LengthAwarePaginator;

class PedidosController extends Controller {
    
    private $dados = ['menu' => 'pedidos'];

    /** Tela inicial com a listagem de pedidos */
    public function index() {
        $pedido = new Pedido();
        $pedidos = $pedido->buscarTodos();
        
        //Faz a paginação do array: https://laracasts.com/discuss/channels/eloquent/pagination-on-array
        // The total number of items. If the `$items` has all the data, you can do something like this:
        $total = count($pedidos);

        // How many items do you want to display.
        $perPage = 5;

        // The index page.
        $currentPage = 1;

        $pedidosPaginados = new LengthAwarePaginator($pedidos, $total, $perPage, $currentPage);
        
//        print_r($pedidosPaginados); exit();
        $this->dados['pedidos'] = $pedidosPaginados;
        return view('pedidos.listar', $this->dados);
    }   

    /**
     * Remove um pedido cadastrado
     * @param $id id do pedido
     */
    public function excluir(int $id) {
        Pedido::destroy($id);
        return redirect()->route('pedidos.listar')->with('sucesso', 'Pedido excluido');
    }

}
