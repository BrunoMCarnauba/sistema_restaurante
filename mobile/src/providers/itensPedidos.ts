import { number } from 'prop-types';
import { APIProviders } from './api';
import ItemPedido from '../models/itemPedido';

export class ItensPedidosProvider extends APIProviders{

    /**
     * Cadastra um novo item de um pedido
     * @param itemPedido
     */
    public async cadastrar(itemPedido: ItemPedido): Promise<boolean>{ //Como é uma função async, então ela retorna os dados como uma Promise
        // console.log(JSON.stringify({itemPedido: itemPedido}));  return false;  //Para teste
        await this.getToken();  //Autentica o usuário
        return this.api.post('/itenspedidos', {itemPedido: itemPedido}).then((resposta) => {
            return true;
        }).catch((erro) => {
            console.log("Erro no método cadastrar() da classe ItensPedidosProvider. Erro: "+erro);
            return false;
        });
    }

    /**
     * Edita o registro do item de um pedido
     * @param itemPedido
     */
    public async editar(itemPedido: ItemPedido): Promise<boolean>{
        await this.getToken();
        return this.api.put('/itenspedidos/'+itemPedido.id_item_pedido, {itemPedido: itemPedido}).then((resposta) => {
            return true;
        }).catch((erro) => {
            console.log("Erro no método editar() da classe ItensPedidosProvider. Erro: "+erro);
            return false;
        });
    }

    /**
     * Exclui o registro do item de um pedido
     * @param id_item_pedido
     */
    public async excluir(id_item_pedido: number): Promise<boolean>{
        await this.getToken();
        return this.api.delete('/itenspedidos/'+id_item_pedido).then((resposta) => {
            return true;
        }).catch((erro) => {
            console.log("Erro no método excluir() da classe ItensPedidosProvider. Erro: "+erro);
            return false;
        });
    }

    /**
     * Busca todos os itens de um pedido e retorna um vetor com todos eles.
     * @param id_pedido
     */
    public async buscarTodos(id_pedido): Promise<ItemPedido[]>{
        await this.getToken();
        return this.api.get('/itenspedidos/listar/'+id_pedido).then((resposta) => {
            return resposta.data;
        }).catch((erro) => {
            console.log("Erro no método buscarTodos() da classe ItensPedidosProvider. Erro: "+erro);
            return [];
        });
    }

    /**
     * Retorna o item de pedido que tem o ID informado
     * @param id_item_pedido
     */
    public async buscar(id_item_pedido: number): Promise<ItemPedido>{
        await this.getToken();
        return this.api.get('/itenspedidos/'+id_item_pedido).then((resposta) => {
            return resposta.data;
        }).catch((erro) => {
            console.log("Erro no método buscar() da classe ItensPedidosProvider. Erro: "+erro);
            return null;
        });
    }
}