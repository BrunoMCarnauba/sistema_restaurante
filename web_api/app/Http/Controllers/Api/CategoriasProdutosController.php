<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;   //Pra poder fazer uma validação manual
use Illuminate\Support\Facades\DB; //Pra poder usar DB::select e outros
use App\Models\CategoriaProduto;

class CategoriasProdutosController extends Controller
{
    /** Cadastra uma nova categoria */
    public function cadastrar(Request $request){
        $validation = Validator::make($request->categoriaProduto, [
           'nome' => 'required'
        ]);
        
        if($validation->fails()){   //O que fazer se a validação falhar
            return response()->json($validation->errors(), 400);
        }else{  //Se tiver passado na validação
            $categoriaProduto = new CategoriaProduto();
            $categoriaProduto->nome = $request->categoriaProduto['nome'];
            $categoriaProduto->descricao = $request->categoriaProduto['descricao'];
            
            if($categoriaProduto->save() == true){
                return response()->json($categoriaProduto, 201);
            }else{
                return response()->json('Erro ao tentar inserir uma nova categoria de produto.', 400);
            }
        }
    }
    
    /** Atualiza o registro de uma categoria */
    public function atualizar(Request $request, $id){
        $categoriaProduto = CategoriaProduto::where('id_categoria_produto', $id)->firstOrFail(); //Checa se o cargo existe. Se não existir, retorna erro 404 e nem roda as próximas linhas.
        
        $validation = Validator::make($request->categoriaProduto, [
           'nome' => 'required'
        ]);
        
        if($validation->fails()){   //O que fazer se a validação falhar
            return response()->json($validation->errors(), 400);
        }else{  //Se tiver passado na validação
            $categoriaProduto = new CategoriaProduto();
            $categoriaProduto->id_categoria_produto = $id;
            $categoriaProduto->nome = $request->categoriaProduto['nome'];
            $categoriaProduto->descricao = $request->categoriaProduto['descricao'];
            
            $categoriaProduto->atualizar();
            
            return response()->json($categoriaProduto, 200);
        }
    }
    
    /** Retorna a lista de categorias de produtos cadastradas */
    public function listar(Request $request){
        $categoriaProduto = new CategoriaProduto();
        $categoriasProdutos = $categoriaProduto->buscarTodos();
        return response()->json($categoriasProdutos, 200);
    }
    
    /** Busca pela categoria de produto de determinado ID */
    public function buscar($id){
        $categoriaProduto = new CategoriaProduto();
        $categoriaProduto = $categoriaProduto->buscar($id);
                
        if($categoriaProduto != null){
            return response()->json($categoriaProduto, 200);
        }else{
            return response()->json('A categoria de produto solicitada não foi encontrada.', 404);
        }
    }
    
    /** Remove uma categoria de produto */
    public function remover(Request $request, $id){
        $categoriaProduto= CategoriaProduto::where('id_categoria_produto', $id)->firstOrFail(); //Se não encontrar cargo com determinado ID, automaticamente retorna falha 404 e não executa as linhas a seguir.
        $categoriaProduto->delete();
        return response()->json('Categoria de produto removida com sucesso.', 200);
    }
}
