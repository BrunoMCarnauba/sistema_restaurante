<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; //Pra poder usar DB::select e outros
use App\Models\Cargo;

class Funcionario extends Model
{
    protected $table = 'funcionarios';
    protected $primaryKey = 'id_funcionario';
    
    //Não protege nenhum campo
    protected $guarded = [];

    //Não exibe esses campos
    protected $hidden = ['senha', 'created_at', 'updated_at', 'deleted_at'];
    
    /*Adiciona variável $cargo aos "attributtes" do Larável, permitindo ser impresso objeto dentro de objeto ao torná-lo um Json
    variáveis aos attributes - Laravel: https://stackoverflow.com/questions/43756564/modify-a-custom-attribute-of-an-eloquent-model */
    public function setCargo($cargo){
        $this->attributes['cargo'] = $cargo;
    }
    
    
    /**
     * Autentica o usuário pelo email e senha - Retorna os dados do funcionário se ele existir. Caso contrário, retorna null.
     */
    public function autenticar($email, $senha){
        $funcionario = Funcionario::where('email', $email)->where('senha', md5($senha))->first();
        
        if($funcionario != null){
            $this->id_funcionario = $funcionario->id_funcionario;
            $this->nome = $funcionario->nome;
            $this->email = $funcionario->email;
            $this->senha = $funcionario->senha;
            
            $cargo = new Cargo;
            $this->cargo = $cargo->buscar($funcionario->cargo_id);
            
//            print_r($this); exit();
            return $this;
        }
        
        return null;
    }
    
    public function buscarTodos(){
        $resultadosBusca = DB::select('SELECT f.id_funcionario, f.nome as nome_funcionario, f.email, f.senha, f.cargo_id, c.nome as nome_cargo, c.descricao, c.salario, c.tudo_permitido FROM funcionarios as f, cargos as c WHERE f.cargo_id = c.id_cargo');
        
        $arrayFuncionarios = [];
        foreach ($resultadosBusca as $funcionario){
            $novoObj = new Funcionario;
            $novoObj->id_funcionario = $funcionario->id_funcionario;
            $novoObj->nome = $funcionario->nome_funcionario;
            $novoObj->email = $funcionario->email;
            $novoObj->senha = $funcionario->senha;
            $novoObj->cargo_id = $funcionario->cargo_id;
            
            $cargo = new Cargo;
            $cargo->id_cargo = $funcionario->cargo_id;
            $cargo->nome = $funcionario->nome_cargo;
            $cargo->descricao = $funcionario->descricao;
            $cargo->salario = $funcionario->salario;
            $cargo->tudo_permitido = $funcionario->tudo_permitido;
            
            $novoObj->cargo = $cargo;
            
            array_push($arrayFuncionarios, $novoObj);   //Adicionando ao array
        }
        
        return $arrayFuncionarios;
    }
    
    public function buscar($idFuncionario){
        $resultadoBusca = DB::select('SELECT f.id_funcionario, f.nome as nome_funcionario, f.email, f.senha, f.cargo_id, c.nome as nome_cargo, c.descricao, c.salario, c.tudo_permitido FROM funcionarios as f, cargos as c WHERE f.cargo_id = c.id_cargo and f.id_funcionario = ?', [$idFuncionario]);
//        print_r($resultadoBusca); exit();;
        
        $novoObj = new Funcionario;
        if($resultadoBusca != null){
            $novoObj->id_funcionario = $resultadoBusca[0]->id_funcionario;
            $novoObj->nome = $resultadoBusca[0]->nome_funcionario;
            $novoObj->email = $resultadoBusca[0]->email;
            $novoObj->senha = $resultadoBusca[0]->senha;
            $novoObj->cargo_id = $resultadoBusca[0]->cargo_id;
            
            $cargo = new Cargo;
            $cargo->id_cargo = $resultadoBusca[0]->cargo_id;
            $cargo->nome = $resultadoBusca[0]->nome_cargo;
            $cargo->descricao = $resultadoBusca[0]->descricao;
            $cargo->salario = $resultadoBusca[0]->salario;
            $cargo->tudo_permitido = $resultadoBusca[0]->tudo_permitido;
            
            $novoObj->cargo = $cargo;
        }
        
        return $novoObj;
    }
    
    public function atualizar(){
        $instrucaoSQL = 'UPDATE funcionarios SET nome = ?, email = ?, cargo_id  = ?';
        $dados = [$this->nome, $this->email, $this->cargo_id];

        if ($this->senha != null){
            $instrucaoSQL.=', senha = ?';
            array_push($dados, $this->senha);
        }
        $instrucaoSQL.= " WHERE id_funcionario = ?";
        array_push($dados, $this->id_funcionario);
        DB::update($instrucaoSQL, $dados);
    }
    
}
