<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;   //Pra poder fazer uma validação manual
use Illuminate\Support\Facades\DB; //Pra poder usar DB::select e outros
use App\Models\Mesa;


/**
 * @package API
 * Classe responsável por Controlar as requisições da API envolvendo as mesas
 */
class MesasController extends Controller
{
    /** Cadastra uma nova mesa */
    public function cadastrar(Request $request){
        $validation = Validator::make($request->mesa, [
           'id_mesa' => 'integer|unique:mesas,id_mesa',
           'pedido_id' => 'integer'
        ]);
        
        if($validation->fails()){   //O que fazer se a validação falhar
            return response()->json($validation->errors(), 400);
        }else{  //Se tiver passado na validação
            $mesa = new Mesa();
            if(isset($request->mesa['id_mesa']) == true){
                $mesa->id_mesa = $request->mesa['id_mesa'];
            }
            if(isset($request->mesa['pedido_id']) == true){  //A função isset() verifica se a variável existe - Para que não dê erro de undefined index
                $mesa->pedido_id = $request->mesa['pedido_id'];
            }
            if(isset($request->mesa['status']) == true){
                $mesa->status = $request->mesa['status'];   
            }
//            unset($produto->imagem); //Remove para não salvar a imagem como base64 no banco.
            
            if($mesa->save() == true){   //Insere os dados no banco (Obs.: A variável $mesa após o save, automaticamente recebe o id_mesa).
                return response()->json($mesa, 201);
            }else{
                return response()->json('Erro ao tentar inserir uma nova mesa.', 400);
            }
        }
    }
    
    /** Atualiza o registro de uma mesa */
    public function atualizar(Request $request, $id){
        $mesa = Mesa::where('id_mesa', $id)->firstOrFail(); //Checa se o produto existe. Se não existir, retorna erro 404 e nem roda as próximas linhas.
        
        $validation = Validator::make($request->mesa, [
           'id_mesa' => 'integer|unique:mesas,id_mesa,'.$id.',id_mesa',
           'pedido_id' => 'integer|nullable'
        ]);
        
        if($validation->fails()){   //O que fazer se a validação falhar
            return response()->json($validation->errors(), 400);
        }else{  //Se tiver passado na validação
            $mesa = new Mesa();
            $mesa->id_mesa = $id;
            if(isset($request->mesa['pedido_id']) == true){  //A função isset() verifica se a variável existe - Para que não dê erro de undefined index
                $mesa->pedido_id = $request->mesa['pedido_id'];
            }
            if(isset($request->mesa['status']) == true){
                $mesa->status = $request->mesa['status'];   
            }
            
            $mesa->atualizar();
            
            return response()->json($mesa, 200);
        }
    }
    
    /** Retorna a lista de mesas cadastradas */
    public function listar(Request $request){
        $mesa = new Mesa();
        $mesas = $mesa->buscarTodos();
        return response()->json($mesas, 200);
    }
    
    /** Busca pela mesa de determinado ID */
    public function buscar($id){
        $mesa = new Mesa();
        $mesa = $mesa->buscar($id);
                
        if($mesa != null){
            return response()->json($mesa, 200);
        }else{
            return response()->json('A mesa solicitada não foi encontrada.', 404);
        }
    }
    
    /** Remove uma mesa */
    public function remover(Request $request, $id){
        $mesa = Mesa::where('id_mesa', $id)->firstOrFail(); //Se não encontrar funcionário com determinado ID, automaticamente retorna falha 404 e não executa as linhas a seguir.
        $mesa->delete();
        return response()->json('Mesa removida com sucesso.', 200);
    }
}
