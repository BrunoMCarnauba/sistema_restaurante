<?php

namespace App\Models;
use Illuminate\Support\Facades\DB; //Pra poder usar DB::select e outros
use App\Models\Pedido;
use App\Models\ItemPedido;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $table = 'mesas';
    protected $primaryKey = 'id_mesa';
    
    //Não protege nenhum campo
    protected $guarded = [];
    
    /*Adiciona variável $pedido aos "attributtes" do Larável, permitindo ser impresso objeto dentro de objeto ao torná-lo um Json
    variáveis aos attributes - Laravel: https://stackoverflow.com/questions/43756564/modify-a-custom-attribute-of-an-eloquent-model */
    public function setPedido($pedido)
    {
        $this->attributes['pedido'] = $pedido;
    }
    
    
    public function buscarTodos(){
        //return Mesa::paginate(10);
        $resultadosBusca = DB::select('SELECT id_mesa, pedido_id, status FROM mesas as m ORDER BY id_mesa ASC');
        
        $arrayMesas = [];
        foreach ($resultadosBusca as $mesa){
            $novoObj = new Mesa();
            
            $novoObj->id_mesa = $mesa->id_mesa;
            $novoObj->status = $mesa->status;
            $novoObj->pedido = null;
            
            if($mesa->pedido_id != null){
                $pedido = new Pedido();
                $novoObj->pedido_id = $mesa->pedido_id;
                $novoObj->pedido = $pedido->buscar($mesa->pedido_id);
            }
            
            array_push($arrayMesas, $novoObj);
        }
        
        return $arrayMesas;;
    }
    
    public function buscar($idMesa){
        $resultadoBusca = DB::select('SELECT id_mesa, pedido_id, status FROM mesas as m WHERE id_mesa = ?', [$idMesa]);
//        print_r($resultadoBusca); exit();;

        $novoObj = new Pedido;
        if($resultadoBusca != null){
            $novoObj = new Mesa();
            
            $novoObj->id_mesa = $resultadoBusca[0]->id_mesa;
            $novoObj->status = $resultadoBusca[0]->status;
            $novoObj->pedido = null;
         
            if($resultadoBusca[0]->pedido_id != null){
                $pedido = new Pedido();
                $novoObj->pedido_id = $resultadoBusca[0]->pedido_id;
                $novoObj->pedido = $pedido->buscar($resultadoBusca[0]->pedido_id);
            }
        }
        
        return $novoObj;
    }
    
    public function atualizar(){
        $instrucaoSQL = 'UPDATE mesas SET pedido_id = ?';
        $dados = [$this->pedido_id];
        
        //Os If abaixo não é obrigatório. Poderia ter deixado tudo em uma só string e só array, portando que os dados tenham sido recebidos corretamente (os obrigatórios estando preenchjidos e os outros podendo estar como null).
        //A forma como foi feita foi pra caso faltasse o determinado atributo ou falhasse na regra. (Foi pra teste)
        if($this->status != null){
            $instrucaoSQL .= ', status = ?';
            array_push($dados, $this->status);
        }
        
//        if($this->pedido_id != null && $this->status == null)
//            $instrucaoSQL = substr($instrucaoSQL, 0, -1);   //Apaga a vírgula que sobrou no final
        
        $instrucaoSQL .= " WHERE id_mesa = ?";
        array_push($dados, $this->id_mesa);
        
        DB::update($instrucaoSQL, $dados);
    }
    
    /* Atualiza o status da mesa de acordo com o status dos itens do pedido */
    public function atualizarStatus($id_pedido){  //Seria interessante colocar isso em uma trigger/gatilho de banco de dados
        $resultadoBusca = DB::select('SELECT id_mesa, pedido_id, status FROM mesas as m WHERE pedido_id = ?', [$id_pedido]);
        if($resultadoBusca != null){
            $itemPedido = new ItemPedido();
            $instrucaoSQL = 'UPDATE mesas SET status = ? WHERE pedido_id = ?';

            if($itemPedido->checarSeItensProntos($id_pedido) == true){
                DB::update($instrucaoSQL, ['Ocupado', $id_pedido]);
            }else{
                DB::update($instrucaoSQL, ['Aguardando', $id_pedido]);
            }
        }
    }

}

















