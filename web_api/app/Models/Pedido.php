<?php

namespace App\Models;
use Illuminate\Support\Facades\DB; //Pra poder usar DB::select e outros
use App\Models\ItemPedido;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table = 'pedidos';
    protected $primaryKey = 'id_pedido';
    
    //Não protege nenhum campo
    protected $guarded = [];
    
    public $itensPedido = null;
    
    public function buscarTodos(){
        //return Pedido::paginate(10);
        $resultadosBusca = DB::select('SELECT id_pedido, valor_total, status_ativo FROM pedidos ORDER BY created_at DESC');
        
        $arrayPedidos = [];
        $itemPedido = new ItemPedido();
        foreach ($resultadosBusca as $pedido){
            $novoObj = new Pedido();
            $arrayItensPedidos = [];
            
            $novoObj->id_pedido = $pedido->id_pedido;
            $novoObj->valor_total = $pedido->valor_total;
            $novoObj->status_ativo = $pedido->status_ativo;
            $novoObj->itensPedido = $itemPedido->buscarTodos($pedido->id_pedido);
            
            array_push($arrayPedidos, $novoObj);
        }
        
        return $arrayPedidos;
    }
    
    public function buscar($idPedido){
        $resultadoBusca = DB::select('SELECT id_pedido, valor_total, status_ativo FROM pedidos WHERE id_pedido = ?', [$idPedido]);
//        print_r($resultadoBusca); exit();;
        
        $novoObj = new Pedido;
        $itemPedido = new ItemPedido();
        if($resultadoBusca != null){
            $novoObj->id_pedido = $resultadoBusca[0]->id_pedido;
            $novoObj->valor_total = $resultadoBusca[0]->valor_total;
            $novoObj->status_ativo = $resultadoBusca[0]->status_ativo;
            $novoObj->itensPedido = $itemPedido->buscarTodos($resultadoBusca[0]->id_pedido);
        }
        
        return $novoObj;
    }
    
    public function contarPedidosAtivos(){
        $totalPedidosAtivos = DB::select('SELECT count(1) as pedidos_ativos FROM pedidos WHERE status_ativo = true');
//        print_r($totalItens[0]->total_itens); exit();
        return $totalPedidosAtivos[0]->pedidos_ativos;
    }
 
    public function atualizar(){
        if($this->status_ativo === null){
            $this->status_ativo = true;
        }

        $instrucaoSQL = 'UPDATE pedidos SET status_ativo = ? WHERE id_pedido = ?';
        $dados = [$this->status_ativo, $this->id_pedido];

        DB::update($instrucaoSQL, $dados);
    }
    
    public function atualizarValorTotal($id_pedido){    //Seria interessante colocar isso em uma trigger/gatilho de banco de dados
        $itemPedido = new ItemPedido();
        
        $this->valor_total = $itemPedido->somarValores($id_pedido);
        
        $instrucaoSQL = 'UPDATE pedidos SET valor_total = ? WHERE id_pedido = ?';
        $dados = [$this->valor_total, $id_pedido];

        DB::update($instrucaoSQL, $dados);
    }
    
}
