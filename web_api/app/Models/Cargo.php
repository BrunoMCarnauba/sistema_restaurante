<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; //Pra poder usar DB::select e outros

class Cargo extends Model
{
    /* O model está vinculado a uma tabela do banco. Seus atributos são as colunas da tabela. */
    protected $table = 'cargos'; /* Nome da tabela do banco de dados */
    protected $primaryKey = 'id_cargo'; /* Chave primária da tabela no banco de dados */
    
    //Não exibe esses campos
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    
    public function buscarTodos(){
        $cargos = Cargo::all(); /* Retorna um vetor com todos as linhas salvas do banco. */
        return $cargos;
    }

    public function buscar($idCargo){
        $resultadoBusca = Cargo::where('id_cargo', $idCargo)->first();
        
        if($resultadoBusca != null){
            $this->id_cargo = $resultadoBusca->id_cargo;
            $this->nome = $resultadoBusca->nome;
            $this->descricao = $resultadoBusca->descricao;
            $this->salario = $resultadoBusca->salario;
            $this->tudo_permitido = $resultadoBusca->tudo_permitido;   
        }
        
        return $this;
    }
    
    public function atualizar(){
        $instrucaoSQL = 'UPDATE cargos SET nome = ?, descricao = ?, salario = ?, tudo_permitido = ? WHERE id_cargo = ?';
        $dados = [$this->nome, $this->descricao, $this->salario, $this->tudo_permitido, $this->id_cargo];
        
        DB::update($instrucaoSQL, $dados);
    }
    
}
