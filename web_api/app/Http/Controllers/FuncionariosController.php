<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Funcionario;
use \App\Models\Cargo;
use Illuminate\Pagination\LengthAwarePaginator;


class FuncionariosController extends Controller
{
    private $dados = ['menu' => 'funcionarios'];

    /** Lista os funcionários */
    public function index() {
        $funcionario = new Funcionario;
        $funcionarios = $funcionario->buscarTodos();
        
//        print_r(Funcionario::paginate(10)); exit();   //Como estava antes
        
        //Faz a paginação do array: https://laracasts.com/discuss/channels/eloquent/pagination-on-array
        // The total number of items. If the `$items` has all the data, you can do something like this:
        $total = count($funcionarios);

        // How many items do you want to display.
        $perPage = 5;

        // The index page.
        $currentPage = 1;

        $funcionariosPaginados = new LengthAwarePaginator($funcionarios, $total, $perPage, $currentPage);

//        print_r($paginator); exit();
        
        $this->dados['funcionarios'] = $funcionariosPaginados;
        return view('funcionarios.listar', $this->dados);
    }

    /** 
     * Abre a tela cadastrar novo funcionário
     */
    public function novo() {
        $this->dados['funcionario'] = new Funcionario();
        $cargo = new Cargo();
        $this->dados['cargos'] = $cargo->buscarTodos();
        
        return view('funcionarios.novo', $this->dados);
    }

    /** 
     * Tenta salvar um novo funcionário
     */
    public function cadastrar(Request $request) {
        $request->validate([
            'nome'  => 'required',
            'email' => 'required|email|unique:funcionarios,email',
            'senha'  => 'required|min:6',
            'cargo' => 'not_in:"Selecione"'
        ]);
        //Se tiver passado na validação, roda o que tiver abaixo
        
        $dados = $request->all();
        $dados['senha'] = md5($dados['senha']); //Criptografa a senha
        
        $funcionario = new Funcionario;
        $funcionario->nome = $dados['nome'];
        $funcionario->email = $dados['email'];
        $funcionario->senha = $request['senha'];
        $funcionario->cargo_id = $request['cargo'];
        $funcionario->save();
        
//        Funcionario::create($dados);

        return redirect()->route('funcionarios.listar')->with(['sucesso' => 'Funcionário cadastrado com sucesso']);
    }

    /** 
     * Abre a tela de editar funcionário
     * @param $id id do funcionário
     */
    public function edicao(int $id) {
        $funcionario = new Funcionario();
        $funcionario = $funcionario->buscar($id);
        $this->dados['funcionario'] = $funcionario;
        
        $cargo = new Cargo();
        $this->dados['cargos'] = $cargo->buscarTodos();
        
        return view('funcionarios.edicao', $this->dados);
    }
    
    /** Tenta editar um funcionário e salvar no banco
     * @param $id id do funcionário
     */
    public function editar(Request $request, int $id) {
        $request->validate([
            'nome'  => 'required',
            'email' => 'required|email|unique:funcionarios,email,'.$id.',id_funcionario',
            'cargo' => 'integer|not_in:"Selecione um cargo"', /* Não esteja selecionado a palavra "Selecione" */
        ]);
            
        $dados = $request->except(['_token']);
        
        $funcionario = new Funcionario;
        $funcionario->id_funcionario = $id;
        $funcionario->nome = $dados['nome'];
        $funcionario->email = $dados['email'];
        if (!empty($dados['senha']))
            $funcionario->senha = md5($dados['senha']);
//        else unset($dados['senha']);
        $funcionario->cargo_id = $dados['cargo'];
        $funcionario->atualizar();
        
//                Funcionario::where('id_funcionario', $id)->update($dados);

        return redirect()->route('funcionarios.listar')->with(['sucesso' => 'Funcionário editado com sucesso']);
    }
    
    /** Remove um funcionário
     * @param $id id do funcionário
     */
    public function excluir(int $id) {
        Funcionario::destroy($id);
        return redirect()->route('funcionarios.listar')->with('sucesso', 'Funcionário excluido');
    }
}
