<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;   //Pra poder fazer uma validação manual
use Illuminate\Support\Facades\DB; //Pra poder usar DB::select e outros
use App\Models\Pedido;

 /** @package API
 * Classe responsável por Controlar as requisições da API envolvendo pedidos
 */
class PedidosController extends Controller
{
    /** Cadastra um novo pedido */
    public function cadastrar(Request $request){
        $validation = Validator::make($request->pedido, [
            
        ]);
        
        if($validation->fails()){   //O que fazer se a validação falhar
            return response()->json($validation->errors(), 400);
        }else{  //Se tiver passado na validação
            $pedido = new Pedido();
            $pedido->valor_total = $request->pedido['valor_total'];
            $pedido->status_ativo = $request->pedido['status_ativo'];
            if($pedido->status_ativo === null){
                $pedido->status_ativo = true;
            }
            $pedido->save();   //Insere os dados no banco (Obs.: A variável $pedido após o save, automaticamente recebe o id_pedido).
            
            return response()->json($pedido, 201);
        }
    }
    
    /** Atualiza o registro de um pedido */
    public function atualizar(Request $request, $id){
        $pedido = Pedido::where('id_pedido', $id)->firstOrFail(); //Checa se o produto existe. Se não existir, retorna erro 404 e nem roda as próximas linhas.
        
        $validation = Validator::make($request->pedido, [
            
        ]);
        
        if($validation->fails()){   //O que fazer se a validação falhar
            return response()->json($validation->errors(), 400);
        }else{  //Se tiver passado na validação
            $pedido = new Pedido();
            $pedido->id_pedido = $id;
//            $pedido->valor_total = $request->pedido['valor_total'];
            $pedido->status_ativo = $request->pedido['status_ativo'];
            
            $pedido->atualizar();
            
            return response()->json($pedido, 200);
        }
    }
    
    /** Retorna a lista de pedidos cadastrados */
    public function listar(Request $request){
        $pedido = new Pedido();
        $pedidos = $pedido->buscarTodos();
        return response()->json($pedidos, 200);
    }
    
    /** Busca pelo pedido de determinado ID */
    public function buscar($id){
        $pedido = new Pedido();
        $pedido = $pedido->buscar($id);
                
        if($pedido != null){
            return response()->json($pedido, 200);
        }else{
            return response()->json('O pedido solicitado não foi encontrado.', 404);
        }
    }
    
    /** Remove um pedido */
    public function remover(Request $request, $id){
        $pedido = Pedido::where('id_pedido', $id)->firstOrFail(); //Se não encontrar funcionário com determinado ID, automaticamente retorna falha 404 e não executa as linhas a seguir.
        $pedido->delete();
        return response()->json('Pedido removido com sucesso.', 200);
    }
}