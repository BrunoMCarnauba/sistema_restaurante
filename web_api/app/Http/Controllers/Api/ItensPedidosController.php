<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;   //Pra poder fazer uma validação manual
use Illuminate\Support\Facades\DB; //Pra poder usar DB::select e outros
use App\Models\ItemPedido;

class ItensPedidosController extends Controller
{
    /** Cadastra um novo item de pedido */
    public function cadastrar(Request $request){
        $validation = Validator::make($request->itemPedido, [
            'pedido_id' => 'required|integer',
            'produto_id' => 'required|integer',
            'quantidade' => 'required|integer',
            'status_pronto' => 'boolean'
        ]);
        //Precisa calcular o preço total do item_pedido e do pedido
        
        if($validation->fails()){   //O que fazer se a validação falhar
            return response()->json($validation->errors(), 400);
        }else{  //Se tiver passado na validação
            $itemPedido = new ItemPedido();
            $itemPedido->pedido_id = $request->itemPedido['pedido_id'];
            $itemPedido->produto_id = $request->itemPedido['produto_id'];
            $itemPedido->quantidade = $request->itemPedido['quantidade'];
            $itemPedido->status_pronto = $request->itemPedido['status_pronto'];
            if(isset($request->itemPedido['comentario']) == true){
                $itemPedido->comentario = $request->itemPedido['comentario'];
            }
            $itemPedido->cadastrar();   //Salva o item (com o cálculo do valor total) e atualizar o valor_total do pedido.
            
            return response()->json($itemPedido, 201);
        }
    }
    
    /** Atualiza o registro de um item */
    public function atualizar(Request $request, $id){
        $itemPedido = ItemPedido::where('id_item_pedido', $id)->firstOrFail(); //Checa se o produto existe. Se não existir, retorna erro 404 e nem roda as próximas linhas.
        
        $validation = Validator::make($request->itemPedido, [
            'pedido_id' => 'required|integer',
            'produto_id' => 'required|integer',
            'quantidade' => 'required|integer',
            'status_pronto' => 'required|boolean'
        ]);
        
        if($validation->fails()){   //O que fazer se a validação falhar
            return response()->json($validation->errors(), 400);
        }else{  //Se tiver passado na validação
            $itemPedido = new ItemPedido();
            $itemPedido->id_item_pedido = $id;
            $itemPedido->pedido_id = $request->itemPedido['pedido_id'];
            $itemPedido->produto_id = $request->itemPedido['produto_id'];
            $itemPedido->quantidade = $request->itemPedido['quantidade'];
            $itemPedido->status_pronto = $request->itemPedido['status_pronto'];
            if(isset($request->itemPedido['comentario']) == true){
                $itemPedido->comentario = $request->itemPedido['comentario'];
            }
            
            $itemPedido->atualizar();
            
            return response()->json($itemPedido, 200);
        }
    }
    
    /** Retorna a lista de itens cadastrados */
    public function listar(Request $request, $idPedido){
        $itemPedido = new ItemPedido();
        $itensPedido = $itemPedido->buscarTodos($idPedido);
        return response()->json($itensPedido, 200);
    }
    
    /** Busca pelos itens de determinado ID */
    public function buscar($id){
        $itemPedido = new ItemPedido();
        $itemPedido = $itemPedido->buscar($id);
                
        if($itemPedido != null){
            return response()->json($itemPedido, 200);
        }else{
            return response()->json('O item solicitado não foi encontrado.', 404);
        };
    }
    
    /** Remove um item de um pedido */
    public function remover(Request $request, $id){
        $itemPedido = ItemPedido::where('id_item_pedido', $id)->firstOrFail(); //Se não encontrar funcionário com determinado ID, automaticamente retorna falha 404 e não executa as linhas a seguir.
        $itemPedido->remover(); //Remove e atualiza o valor total do pedido
        return response()->json('Item removido com sucesso.', 200);
    }
}
