<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; //Pra poder usar DB::select e outros
use App\Models\CategoriaProduto;

class Produto extends Model
{
    protected $table = 'produtos';
    protected $primaryKey = 'id_produto';
    
    //Não protege nenhum campo
    protected $guarded = [];

    //Esconde os campos
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    /*Adiciona variável aos "attributtes" dessa classe - https://stackoverflow.com/questions/43756564/modify-a-custom-attribute-of-an-eloquent-model */
    public function setImagem($imagem){
        $this->attributes['imagem'] = $imagem;
    }
    
    public function setCategoriaProduto($categoriaProduto){
        $this->attributes['categoriaProduto'] = $categoriaProduto;
    }
    
    /**
     * Altera para a imagem ser exibida com a URL inteira.
     */
    public function getImagemAttribute($value) {
        //if (!empty($value))
        //    return url('storage/imagens/produtos/'.$value);
        return $value;
    }

    /**
     * Altera como a data deve ser exibida
     */
    public function getDataAttribute($value) {
        return date('d/m/Y', strtotime($value));
    }
    
    public function buscarTodos(){
        $resultadosBusca = DB::select('SELECT p.id_produto, p.nome, p.descricao, p.categoria_id, p.calorias, p.preco, c.nome as nome_categoria, c.descricao as descricao_categoria FROM produtos as p, categorias_produtos as c WHERE p.categoria_id = c.id_categoria_produto');
        
        $arrayProdutos = [];
        foreach ($resultadosBusca as $produto){
            $novoObj = new Produto;
            $novoObj->id_produto = $produto->id_produto;
            $novoObj->nome = $produto->nome;
            $novoObj->descricao = $produto->descricao;
            $novoObj->categoria_id = $produto->categoria_id;
            $novoObj->calorias = $produto->calorias;
            $novoObj->preco = $produto->preco;
            
            $categoriaProduto = new CategoriaProduto;
            $categoriaProduto->id_categoria_produto = $produto->categoria_id;
            $categoriaProduto->nome = $produto->nome_categoria;
            $categoriaProduto->descricao = $produto->descricao_categoria;
            
            $novoObj->categoriaProduto = $categoriaProduto;
            
            array_push($arrayProdutos, $novoObj);   //Adicionando ao array
        }
        
        return $arrayProdutos;
    }
    
    public function buscar($idProduto){
        $resultadoBusca = DB::select('SELECT p.id_produto, p.nome, p.descricao, p.categoria_id, p.calorias, p.preco, c.nome as nome_categoria, c.descricao as descricao_categoria FROM produtos as p, categorias_produtos as c WHERE p.categoria_id = c.id_categoria_produto and p.id_produto = ?', [$idProduto]);
//        print_r($resultadoBusca); exit();;
        
        $novoObj = new Produto();
        if($resultadoBusca != null){
            $novoObj->id_produto = $resultadoBusca[0]->id_produto;
            $novoObj->nome = $resultadoBusca[0]->nome;
            $novoObj->descricao = $resultadoBusca[0]->descricao;
            $novoObj->categoria_id = $resultadoBusca[0]->categoria_id;
            $novoObj->calorias = $resultadoBusca[0]->calorias;
            $novoObj->preco = $resultadoBusca[0]->preco;
            
            $categoriaProduto = new CategoriaProduto;
            $categoriaProduto->id_categoria_produto = $resultadoBusca[0]->categoria_id;
            $categoriaProduto->nome = $resultadoBusca[0]->nome_categoria;
            $categoriaProduto->descricao = $resultadoBusca[0]->descricao_categoria;
            
            $novoObj->categoriaProduto = $categoriaProduto;
            
            if( file_exists(storage_path('app/public/imagens/produtos/'.'produto_'.$resultadoBusca[0]->id_produto.'.jpg')) ){
                $caminhoImagem = storage_path('app/public/imagens/produtos/'.'produto_'.$resultadoBusca[0]->id_produto.'.jpg');
                $formatoImagem = pathinfo($caminhoImagem, PATHINFO_EXTENSION);
                $imagem = file_get_contents($caminhoImagem);
                $base64 = 'data:image/' . $formatoImagem . ';base64,' . base64_encode($imagem);
                $novoObj->imagem = $base64;
            }
        }
        
        return $novoObj;
    }
    
    public function atualizar(){
        $instrucaoSQL = 'UPDATE produtos SET nome = ?, categoria_id = ?, preco = ?';
        $dados = [$this->nome, $this->categoria_id, $this->preco];
        
        //Os If's abaixo não são obrigatórios. Poderia ter deixado tudo em uma só string e só array, contando com que os dados tenham sido recebidos corretamente (os obrigatórios estando preenchhidos e os outros podendo estar como null).
        //A forma como foi feita foi pra caso faltasse o determinado atributo ou falhasse na regra. (Foi pra teste)
        if ($this->descricao != null){
            $instrucaoSQL.=', descricao = ?';
            array_push($dados, $this->descricao);
        }
        if ($this->calorias != null){
            $instrucaoSQL.=', calorias = ?';
            array_push($dados, $this->calorias);
        }
        
        $instrucaoSQL.= " WHERE id_produto = ?";
        array_push($dados, $this->id_produto);
        
        DB::update($instrucaoSQL, $dados);
    }
    
}
