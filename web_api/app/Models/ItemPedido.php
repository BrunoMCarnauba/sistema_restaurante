<?php

namespace App\Models;
use Illuminate\Support\Facades\DB; //Pra poder usar DB::select e outros
use App\Models\Pedido;
use App\Models\Mesa;

use Illuminate\Database\Eloquent\Model;

class ItemPedido extends Model
{
    protected $table = 'itens_pedidos';
    protected $primaryKey = 'id_item_pedido';
    
    //Não protege nenhum campo
    protected $guarded = [];
    
    /*Adiciona variável $cargo aos "attributtes" do Larável, permitindo ser impresso objeto dentro de objeto ao torná-lo um Json
    variáveis aos attributes - Laravel: https://stackoverflow.com/questions/43756564/modify-a-custom-attribute-of-an-eloquent-model */
    public function setProduto($produto){
        $this->attributes['produto'] = $produto;
    }
    
    
    public function cadastrar(){
        $produto = new Produto();
        $produto = $produto->buscar($this->produto_id);
        
        //Calcula o preço total (preço da unidade do produto * quantidade)
        $this->preco = $produto->preco * $this->quantidade;
        
        if($this->status_pronto == null){
            $this->status_pronto = false;
        }
        
        //Salva na tabela itens_pedido
        $this->save();
        
        //Atualiza o valor total do pedido (soma de todos os valores totais dos itens)
        $pedido = new Pedido();
        $pedido->atualizarValorTotal($this->pedido_id);
        
        //Atualiza o status da mesa (Aguardando ou Ocupado)
        $mesa = new Mesa();
        $mesa->atualizarStatus($this->pedido_id);
    }
    
    public function atualizar(){
        $produto = new Produto();
        $this->produto = $produto->buscar($this->produto_id);
        
        //Calcula o preço total (preço da unidade do produto * quantidade)
        $this->preco = $this->produto->preco * $this->quantidade;
        
        if($this->status_pronto == null){
            $this->status_pronto = false;
        }
        
        //Atualiza o registro do item 
        $instrucaoSQL = 'UPDATE itens_pedidos SET produto_id = ?, quantidade = ?, comentario = ?, preco = ?, status_pronto = ? WHERE id_item_pedido = ?';
        $dados = [$this->produto_id, $this->quantidade, $this->comentario, $this->preco, $this->status_pronto, $this->id_item_pedido];

        DB::update($instrucaoSQL, $dados);
        
        //Atualiza o valor total do pedido (soma de todos os valores totais dos itens)
        $pedido = new Pedido();
        $pedido->atualizarValorTotal($this->pedido_id);
        
        //Atualiza o status da mesa (Aguardando ou Ocupado)
        $mesa = new Mesa();
        $mesa->atualizarStatus($this->pedido_id);
    }
    
    /*
     * Retorna um array com todos os itens do pedido do ID especificado
     * @param $id_pedido
     */
    public function buscarTodos($id_pedido){
        $resultadosBusca = DB::select('SELECT ip.id_item_pedido, ip.produto_id, ip.pedido_id, ip.quantidade, ip.comentario, ip.preco as preco, ip.status_pronto, p.nome, p.descricao, p.categoria_id, p.calorias, p.preco as preco_unidade FROM itens_pedidos as ip, produtos as p WHERE ip.produto_id = p.id_produto and pedido_id = ? ORDER BY ip.created_at DESC',[$id_pedido]);
        
        $arrayPedidos = [];
        foreach ($resultadosBusca as $itemPedido){
            $novoObj = new ItemPedido();
            
            $novoObj->id_item_pedido = $itemPedido->id_item_pedido;
            $novoObj->produto_id = $itemPedido->produto_id;
            $novoObj->pedido_id = $itemPedido->pedido_id;
            $novoObj->quantidade = $itemPedido->quantidade;
            $novoObj->comentario = $itemPedido->comentario;
            $novoObj->preco = $itemPedido->preco;
            $novoObj->status_pronto = $itemPedido->status_pronto;
            
            $produto = new Produto();
            $produto->nome = $itemPedido->nome;
            $produto->descricao = $itemPedido->descricao;
            $produto->categoria_id = $itemPedido->categoria_id;
            $produto->calorias = $itemPedido->calorias;
            $produto->preco = $itemPedido->preco_unidade;
            $novoObj->produto = $produto;
            
            array_push($arrayPedidos, $novoObj);
        }
        
//        print_r($arrayPedidos); exit();
        return $arrayPedidos;
    }
    
    public function buscar($id_item_pedido){
        $resultadoBusca = DB::select('SELECT ip.id_item_pedido, ip.produto_id, ip.pedido_id, ip.quantidade, ip.comentario, ip.preco as preco, ip.status_pronto, p.nome, p.descricao, p.categoria_id, p.calorias, p.preco as preco_unidade FROM itens_pedidos as ip, produtos as p WHERE ip.produto_id = p.id_produto and id_item_pedido = ?', [$id_item_pedido]);
//        print_r($resultadoBusca); exit();;
        
        $novoObj = new ItemPedido;
        if($resultadoBusca != null){
            $novoObj = new ItemPedido();
            
            $novoObj->id_item_pedido = $resultadoBusca[0]->id_item_pedido;
            $novoObj->produto_id = $resultadoBusca[0]->produto_id;
            $novoObj->pedido_id = $resultadoBusca[0]->pedido_id;
            $novoObj->quantidade = $resultadoBusca[0]->quantidade;
            $novoObj->comentario = $resultadoBusca[0]->comentario;
            $novoObj->preco = $resultadoBusca[0]->preco;
            $novoObj->status_pronto = $resultadoBusca[0]->status_pronto;
            
            $produto = new Produto();
            $produto->nome = $resultadoBusca[0]->nome;
            $produto->descricao = $resultadoBusca[0]->descricao;
            $produto->categoria_id = $resultadoBusca[0]->categoria_id;
            $produto->calorias = $resultadoBusca[0]->calorias;
            $produto->preco = $resultadoBusca[0]->preco_unidade;
            $novoObj->produto = $produto;
        }
        
        return $novoObj;
    }
    
    public function remover(){
        $this->delete();
        //Atualiza o valor_total do pedido
        $pedido = new Pedido();
        $pedido->atualizarValorTotal($this->pedido_id);
        //Atualiza o status da mesa (Aguardando ou Ocupado)
        $mesa = new Mesa();
        $mesa->atualizarStatus($this->pedido_id);
    }
    
    /*
     * Faz a soma do preço de todos os itens do pedido
     */
    public function somarValores($pedido_id){
        $somaPrecos = DB::select('SELECT SUM(preco) as valor_total FROM itens_pedidos WHERE pedido_id = ?',[$pedido_id]);
        
        if($somaPrecos[0]->valor_total == null){
            return 0.00;
        }else{
            return $somaPrecos[0]->valor_total;   
        }
    }
    
    /*
     * Conta o total de itens de determinado ID de pedido
     */
    public function contarItens($idPedido){
        //Soma as quantidades para ter o total de itens de determinado pedido
        $totalItens = DB::select('SELECT SUM(quantidade) as total_itens FROM itens_pedidos WHERE pedido_id = ?',[$idPedido]);
//        print_r($totalItens[0]->total_itens); exit();
        if($totalItens[0]->total_itens == null){
            return 0;
        }else{
            return $totalItens[0]->total_itens;
        }
    }
    
    /*
     * Retorna em uma string todos os itens do pedido de determinado ID
     */
    public function itensPedidoString($idPedido){
        $resultadosBusca = DB::select('SELECT ip.id_item_pedido, ip.quantidade, ip.status_pronto, ip.comentario, p.nome FROM produtos p, itens_pedidos as ip WHERE ip.produto_id = p.id_produto and pedido_id = ?',[$idPedido]);
        
        $stringItensPedido = '';
        foreach ($resultadosBusca as $item){
            $stringItensPedido .= $item->quantidade."x ".$item->nome;
            
            if($item->comentario != null){
                $stringItensPedido .= " - ".$item->comentario;
            }

            $stringItensPedido .= " - Status: ";
            if($item->status_pronto == true){
                $stringItensPedido .= "Feito";
            }else{
                $stringItensPedido .= "Aguardando";
            }
            $stringItensPedido .= "\n";
        }
        
        $stringItensPedido .= "Total: ".$this->contarItens($idPedido)." itens";
        
//        print_r($stringItensPedido); exit();
        return $stringItensPedido;
    }

    /* Altera o status do pedido para true */
    public function mudarStatus($pedido_id, $id_item_pedido){
        DB::update('UPDATE itens_pedidos SET status_pronto = ? WHERE id_item_pedido = ?', [true, $id_item_pedido]);
        
        //Atualiza o status da mesa (Aguardando ou Ocupado)
        $mesa = new Mesa();
        $mesa->atualizarStatus($pedido_id);
    }
    
    /* Checa se todos os itens do pedido estão prontos - Retorna true se estiverem */
    public function checarSeItensProntos($pedido_id){
        $resultadoContador = DB::select("SELECT count(1) as numero_pedidos_aguardando FROM itens_pedidos WHERE pedido_id = ? and status_pronto = false", [$pedido_id]);
        if($resultadoContador[0]->numero_pedidos_aguardando != null){
            $resultadoContador = $resultadoContador[0]->numero_pedidos_aguardando;
        }else{
            $resultadoContador = 0;
        }
        
        if($resultadoContador > 0){
            return false;
        }else{
            return true;
        }
    }
    
}
