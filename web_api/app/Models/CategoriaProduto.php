<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; //Pra poder usar DB::select e outros

class CategoriaProduto extends Model
{
    protected $table = 'categorias_produtos';
    protected $primaryKey = 'id_categoria_produto';
    
    //Não protege nenhum campo
    protected $guarded = [];
    
    //Não exibe esses campos
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
    
    
    public function buscarTodos(){
        $resultadosBusca = DB::select('SELECT c.id_categoria_produto, c.nome, c.descricao FROM categorias_produtos as c');
        
        $arrayCategorias = [];
        foreach ($resultadosBusca as $categoriaProduto){
            $novoObj = new CategoriaProduto();
            
            $novoObj->id_categoria_produto = $categoriaProduto->id_categoria_produto;
            $novoObj->nome = $categoriaProduto->nome;
            $novoObj->descricao = $categoriaProduto->descricao;
            
            array_push($arrayCategorias, $novoObj);
        }
        
        return $arrayCategorias;
    }
    
    public function buscar($id_categoria_produto){
        $resultadoBusca = DB::select('SELECT c.id_categoria_produto, c.nome, c.descricao FROM categorias_produtos as c WHERE id_categoria_produto = ?', [$id_categoria_produto]);
//        print_r($resultadoBusca); exit();;
        
        $novoObj = new CategoriaProduto;
        if($resultadoBusca != null){
            $novoObj = new CategoriaProduto();
            
            $novoObj->id_categoria_produto = $resultadoBusca[0]->id_categoria_produto;
            $novoObj->nome = $resultadoBusca[0]->nome;
            $novoObj->descricao = $resultadoBusca[0]->descricao;
        }
        
        return $novoObj;
    }
    
    public function atualizar(){
        $instrucaoSQL = 'UPDATE categorias_produtos SET nome = ?, descricao = ? WHERE id_categoria_produto = ?';
        $dados = [$this->nome, $this->descricao, $this->id_categoria_produto];
        
        DB::update($instrucaoSQL, $dados);
    }
    
}
