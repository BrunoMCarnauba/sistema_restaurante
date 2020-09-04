<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mesa;
use Illuminate\Pagination\LengthAwarePaginator;

class MesaController extends Controller
{

    private $dados = ['menu' => 'mesas'];

    /** Tela inicial com a listagem de mesas */
    public function index() {
        $mesa = new Mesa();
        $mesas = $mesa->buscarTodos();
        
        //Faz a paginação do array: https://laracasts.com/discuss/channels/eloquent/pagination-on-array
        // The total number of items. If the `$items` has all the data, you can do something like this:
        $total = count($mesas);

        // How many items do you want to display.
        $perPage = 5;

        // The index page.
        $currentPage = 1;

        $mesasPaginadas = new LengthAwarePaginator($mesas, $total, $perPage, $currentPage);
        
//        print_r($pedidosPaginados); exit();
        $this->dados['mesas'] = $mesasPaginadas;
        return view('mesas.listar', $this->dados);
    }
    
    /**
     * Remove uma mesa cadastrada
     * @param $id id da mesa
     */
    public function excluir(int $id) {
        Mesa::destroy($id);
        return redirect()->route('pedidos.listar')->with('sucesso', 'Pedido excluido');
    }
    
}
