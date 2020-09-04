<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; //Pra poder usar DB::select e outros
use \App\Models\Funcionario;
use \App\Models\Cargo;
use Firebase\JWT\JWT;

/**
 * @package API
 * Classe responsável por Controlar as requisições da API envolvendo funcionário
 */
class FuncionariosController extends Controller
{
    
    /** Cadastra um novo funcionário */
    public function cadastrar(Request $request){
        //Available Validation Rules: https://laravel.com/docs/5.8/validation#available-validation-rules
        $validation = Validator::make($request->funcionario, [
           'nome' => 'required',
           'email' => 'required|email|unique:funcionarios,email',
           'senha' => 'required|min:6',
           'cargo_id' => 'required|integer'
        ]);
        
        if($validation->fails()){   //O que fazer se a validação falhar
            return response()->json($validation->errors(), 400);
        }else{  //Se tiver passado na validação
            $funcionario = new Funcionario();
            $funcionario->nome = $request->funcionario['nome'];
            $funcionario->email = $request->funcionario['email'];
            $funcionario->senha = md5($request->funcionario['senha']);
            $funcionario->cargo_id = $request->funcionario['cargo_id'];
                    
            $funcionario->save();
            
            return response()->json($funcionario, 201);
        }
    }
    
    /** Atualiza o registro de um funcionário */
    public function atualizar(Request $request, $id){
        $funcionario = Funcionario::where('id_funcionario', $id)->firstOrFail(); //Checa se o produto existe. Se não existir, retorna erro 404 e nem roda as próximas linhas.
        
        //Available Validation Rules: https://laravel.com/docs/5.8/validation#available-validation-rules
        //Validation unique on update: https://stackoverflow.com/questions/23587833/laravel-validation-unique-on-update
        $validation = Validator::make($request->funcionario, [
           'nome' => 'required',
           'email' => 'required|email|unique:funcionarios,email,'.$id.',id_funcionario',
           'cargo_id' => 'required|integer'
        ]);
        
        if($validation->fails()){   //O que fazer se a validação falhar
            return response()->json($validation->errors(), 400);
        }else{  //Se tiver passado na validação
            $funcionario = new Funcionario();
            $funcionario->id_funcionario = $id;
            $funcionario->nome = $request->funcionario['nome'];
            $funcionario->email = $request->funcionario['email'];
            if(isset($request->funcionario['senha']) == true){  //A função isset() verifica se a variável existe - Para que não dê erro de undefined index
                $funcionario->senha = md5($request->funcionario['senha']);
            }
            $funcionario->cargo_id = $request->funcionario['cargo_id'];
            
            $funcionario->atualizar();
            
            return response()->json($funcionario, 200);
        }
    }
    
    /**
     * Autentica o usuário pelo email e senha. Retorna o token gerado no login (para poder acessar as outras rotas) e os dados do usuário.
     */
    public function logar(Request $request) {
        $funcionario = Funcionario::where('email', $request->email)
                            ->where('senha', md5($request->senha))
                            ->firstOrFail(); //Se não achar retorna logo o código de erro 404 e nem executa as linhas a seguir.
        
        $jwt = JWT::encode(['id_funcionario' => $funcionario->id_funcionario], config('jwt.senha'));    //Gera o token passando dados do funcionário e a senha de assinatura do token
        return response()->json(['jwt' => $jwt, 'funcionario' => $funcionario], 200);
    }
    
    /** Retorna a lista de funcionários cadastrados */
    public function listar(Request $request){
        $funcionario = new Funcionario();
        $funcionarios = $funcionario->buscarTodos();
        return response()->json($funcionarios, 200);
    }
    
    /** Busca pelo funcionário de determinado ID */
    public function buscar($id){
        $funcionario = new Funcionario();
        $funcionario = $funcionario->buscar($id);
        if($funcionario != null){
            return response()->json($funcionario, 200);
        }else{
            return response()->json('O funcionário não foi encontrado.', 400);
        }
    }
    
    /** Remove um funcionário */
    public function remover(Request $request, $id){
        $funcionario = Funcionario::where('id_funcionario', $id)->firstOrFail(); //Se não encontrar funcionário com determinado ID, automaticamente retorna falha 404 e não executa as linhas a seguir.
        $funcionario->delete();
        return response()->json('Funcionário removido com sucesso.', 200);
    }
    
    /** 
     * Recebe a imagem na base64
     * @param $uriBase64 | Imagem com toda URI data:image/png;base64,
     * @param $nomeArquivo | Qual nome do arquivo para ser salvo
     */
//    private function salvarImagem(string $uriBase64, string $nomeArquivo) {
//        $vetor = explode(',', $uriBase64);
//        $imagemBase64 = end($vetor);
//        file_put_contents(storage_path('app/public/fotos/'.$nomeArquivo), base64_decode($imagemBase64));
//    }
    
}
