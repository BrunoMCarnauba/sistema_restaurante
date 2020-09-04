<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;   //Pra poder fazer uma validação manual
use Illuminate\Support\Facades\DB; //Pra poder usar DB::select e outros
use App\Models\Cargo;

class CargosController extends Controller
{
    /** Cadastra um novo cargo */
    public function cadastrar(Request $request){
        $validation = Validator::make($request->cargo, [
           'nome' => 'required',
           'tudo_permitido' => 'required|boolean',
           'salario' => 'numeric'
        ]);
        
        if($validation->fails()){   //O que fazer se a validação falhar
            return response()->json($validation->errors(), 400);
        }else{  //Se tiver passado na validação
            $cargo = new Cargo();
            $cargo->nome = $request->cargo['nome'];
            $cargo->descricao = $request->cargo['descricao'];
            $cargo->salario = $request->cargo['salario'];
            $cargo->tudo_permitido = $request->cargo['tudo_permitido'];
            
            if($cargo->save() == true){   //Insere os dados no banco (Obs.: A variável $cargo após o save, automaticamente recebe o id_cargo).
                return response()->json($cargo, 201);
            }else{
                return response()->json('Erro ao tentar inserir um novo cargo.', 400);
            }
        }
    }
    
    /** Atualiza o registro de um cargo */
    public function atualizar(Request $request, $id){
        $cargo = Cargo::where('id_cargo', $id)->firstOrFail(); //Checa se o cargo existe. Se não existir, retorna erro 404 e nem roda as próximas linhas.
        
        $validation = Validator::make($request->cargo, [
           'nome' => 'required',
           'tudo_permitido' => 'required|boolean',
           'salario' => 'numeric'
        ]);
        
        if($validation->fails()){   //O que fazer se a validação falhar
            return response()->json($validation->errors(), 400);
        }else{  //Se tiver passado na validação
            $cargo = new Cargo();
            $cargo->id_cargo = $id;
            $cargo->nome = $request->cargo['nome'];
            $cargo->descricao = $request->cargo['descricao'];
            $cargo->salario = $request->cargo['salario'];
            $cargo->tudo_permitido = $request->cargo['tudo_permitido'];
            
            $cargo->atualizar();
            
            return response()->json($cargo, 200);
        }
    }
    
    /** Retorna a lista de cargos cadastrados */
    public function listar(Request $request){
        $cargo = new Cargo();
        $cargos = $cargo->buscarTodos();
        return response()->json($cargos, 200);
    }
    
    /** Busca pelo cargo de determinado ID */
    public function buscar($id){
        $cargo = new Cargo();
        $cargo = $cargo->buscar($id);
                
        if($cargo != null){
            return response()->json($cargo, 200);
        }else{
            return response()->json('O cargo solicitado não foi encontrado.', 404);
        }
    }
    
    /** Remove um cargo */
    public function remover(Request $request, $id){
        $cargo= Cargo::where('id_cargo', $id)->firstOrFail(); //Se não encontrar cargo com determinado ID, automaticamente retorna falha 404 e não executa as linhas a seguir.
        $cargo->delete();
        return response()->json('Cargo removido com sucesso.', 200);
    }
}
