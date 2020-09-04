<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;   //Pra poder fazer uma validação manual
use Illuminate\Support\Facades\DB; //Pra poder usar DB::select e outros
use \App\Models\Produto;

/**
 * @package API
 * Classe responsável por Controlar as requisições da API envolvendo produto
 */
class ProdutosController extends Controller
{
    
    /** Cadastra um novo produto */
    public function cadastrar(Request $request){
        $validation = Validator::make($request->produto, [
           'nome' => 'required',
           'categoria_id' => 'required|integer',
           'preco' => 'required|numeric',
           'calorias' => 'integer'
        ]);
        
        if($validation->fails()){   //O que fazer se a validação falhar
            return response()->json($validation->errors(), 400);
        }else{  //Se tiver passado na validação
            $produto = new Produto();
            $produto->nome = $request->produto['nome'];
            $produto->categoria_id = $request->produto['categoria_id'];
            $produto->preco = $request->produto['preco'];
            if(isset($request->produto['descricao']) == true){  //A função isset() verifica se a variável existe - Para que não dê erro de undefined index
                $produto->descricao = $request->produto['descricao'];
            }
            if(isset($request->produto['calorias']) == true){
                $produto->calorias = $request->produto['calorias'];   
            }
           //unset($request->produto['imagem']); //Remove para não salvar a imagem como base64 no banco.
            
            $produto->save();   //Insere os dados no banco (Obs.: A variável $produto após o save, automaticamente recebe o id_produto).
            
            //Caso tenha imagem, salva a imagem
           if (!empty($request->produto['imagem']) && substr($request->produto['imagem'], 0, 4) == 'data') {
               $produto->imagem = $nomeArquivo = 'produto_'.$produto->id_produto.'.jpg';
               $this->salvarImagem($request->produto['imagem'], $nomeArquivo);
           }
            
            return response()->json($produto, 201);
        }
    }
    
    /** Atualiza o registro de um produto */
    public function atualizar(Request $request, $id){
        $produto = Produto::where('id_produto', $id)->firstOrFail(); //Checa se o produto existe. Se não existir, retorna erro 404 e nem roda as próximas linhas.
        
        $validation = Validator::make($request->produto, [
           'nome' => 'required',
           'categoria_id' => 'required|integer',
           'preco' => 'required|numeric',
           'calorias' => 'integer'
        ]);
        
        if($validation->fails()){   //O que fazer se a validação falhar
            return response()->json($validation->errors(), 400);
        }else{  //Se tiver passado na validação
            $produto = new Produto();
            $produto->id_produto = $id;
            $produto->nome = $request->produto['nome'];
            $produto->categoria_id = $request->produto['categoria_id'];
            $produto->preco = $request->produto['preco'];
            if(isset($request->produto['descricao']) == true){  //A função isset() verifica se a variável existe - Para que não dê erro de undefined index
                $produto->descricao = $request->produto['descricao'];
            }
            if(isset($request->produto['calorias']) == true){
                $produto->calorias = $request->produto['calorias'];   
            }
            
            $produto->atualizar();
            
            //Caso tenha imagem, salva a imagem
           if (!empty($request->produto['imagem']) && substr($request->produto['imagem'], 0, 4) == 'data') {
               $produto->imagem = $nomeArquivo = 'produto_'.$produto->id_produto.'.jpg';
               $this->salvarImagem($request->produto['imagem'], $nomeArquivo);
           }
            
            return response()->json($produto, 200);
        }
    }
    
    /** Retorna a lista de produtos cadastrados */
    public function listar(Request $request){
        $produto = new Produto();
        $produtos = $produto->buscarTodos();
        return response()->json($produtos, 200);
    }
    
    /** Busca pelo produto de determinado ID */
    public function buscar($id){
        $produto = new Produto();
        $produto = $produto->buscar($id);
                
        if($produto != null){
            return response()->json($produto, 200);
        }else{
            return response()->json('O funcionário não foi encontrado.', 404);
        }
    }
    
    /** Remove um produto */
    public function remover(Request $request, $id){
        $produto = Produto::where('id_produto', $id)->firstOrFail(); //Se não encontrar funcionário com determinado ID, automaticamente retorna falha 404 e não executa as linhas a seguir.
        $produto->delete();
        return response()->json('Produto removido com sucesso.', 200);
    }
    
    /** 
     * Recebe a imagem na base64
     * @param $uriBase64 | Imagem com toda URI data:image/png;base64,
     * @param $nomeArquivo | Qual nome do arquivo para ser salvo
     */
    private function salvarImagem(string $uriBase64, string $nomeArquivo) {
        $vetor = explode(',', $uriBase64);  //Separa o vetor entre a vírgula
        $imagemBase64 = end($vetor);    //Pega a última posição que é tudo após o data data:image/png;base64,
        file_put_contents(storage_path('app/public/imagens/produtos/'.$nomeArquivo), base64_decode($imagemBase64));
    }
}
