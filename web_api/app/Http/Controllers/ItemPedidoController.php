<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\ItemPedido;
use App\Models\Mesa;
use Illuminate\Pagination\LengthAwarePaginator;

class ItemPedidoController extends Controller
{
    private $dados = ['menu' => 'itensPedido'];
    
    /**
     * Busca itens do pedido e chama a tela de visualização deles
     * @param $id_pedido
     */
    public function visualizarItens(int $id_mesa){
        $mesa = new Mesa();
        $mesa = $mesa->buscar($id_mesa);

        $itemPedido = new ItemPedido();
        $itens = $itemPedido->buscarTodos($mesa->pedido_id);

        if($itens != null){
            //Faz a paginação do array: https://laracasts.com/discuss/channels/eloquent/pagination-on-array
            // The total number of items. If the `$items` has all the data, you can do something like this:
            $total = count($itens);

            // How many items do you want to display.
            $perPage = 5;

            // The index page.
            $currentPage = 1;

            $itensPaginados = new LengthAwarePaginator($itens, $total, $perPage, $currentPage);
    //        print_r($itensPaginados); exit();
            
            $this->dados['mesa'] = $mesa;
            $this->dados['itensPedido'] = $itensPaginados;
            $pedido = new Pedido;
            
            return view('itens_pedido.listar', $this->dados);
        }
    }

    /**
     * Muda status do item (De aguardando para pronto)
     * @param $id_item_pedido
     */
    public function mudarStatus($idMesa, $idItem){
        $mesa = new Mesa();
        $mesa = $mesa->buscar($idMesa);
        
        $itemPedido = new ItemPedido();
        $itemPedido->mudarStatus($mesa->pedido_id, $idItem);
        
        return redirect()->route('itens_pedido.visualizar', [$mesa->id_mesa]);
    }
    
    /**
     * Remove um item cadastrado
     * @param $id id do item
     */
    public function excluir(int $id_pedido, int $id_item_pedido) {
        $itemPedido = new ItemPedido();
        
        ItemPedido::destroy($id_item_pedido);
        
        if($itemPedido->contarItens($id_pedido) > 0){
            return redirect()->route('itens_pedido.visualizar', [$id_pedido])->with('sucesso', 'Item excluido');   
        }else{
            return redirect()->route('mesas.listar')->with('sucesso', 'Item excluido');
        }
    }
    
}
