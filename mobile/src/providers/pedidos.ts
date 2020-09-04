import { number } from 'prop-types';
import { APIProviders } from './api';
import Pedido from '../models/pedido';

export class PedidosProvider extends APIProviders{

    /**
     * Cadastra um novo pedido
     * @param pedido
     */
    public async cadastrar(pedido: Pedido): Promise<Pedido>{ //Como é uma função async, então ela retorna os dados como uma Promise
        await this.getToken();  //Autentica o usuário
        return this.api.post('/pedidos', {pedido: pedido}).then((resposta) => {
            return resposta.data;
        }).catch((erro) => {
            console.log("Erro no método cadastrar() da classe PedidosProvider. Erro: "+erro);
            return null;
        });
    }

    /**
     * Edita o registro de um pedido
     * @param pedido
     */
    public async editar(pedido: Pedido): Promise<boolean>{
        await this.getToken();
        return this.api.put('/pedidos/'+pedido.id_pedido, {pedido: pedido}).then((resposta) => {
            return true;
        }).catch((erro) => {
            console.log("Erro no método editar() da classe PedidosProvider. Erro: "+erro);
            return false;
        });
    }

    /**
     * Exclui o registro de um pedido
     * @param id_pedido
     */
    public async excluir(id_pedido: number): Promise<boolean>{
        await this.getToken();
        return this.api.delete('/pedidos/'+id_pedido).then((resposta) => {
            return true;
        }).catch((erro) => {
            console.log("Erro no método excluir() da classe PedidosProvider. Erro: "+erro);
            return false;
        });
    }

    /**
     * Busca todos os pedidos e retorna um vetor com todos eles.
     */
    public async buscarTodos(): Promise<Pedido[]>{
        await this.getToken();
        return this.api.get('/pedidos').then((resposta) => {
            return resposta.data;
        }).catch((erro) => {
            console.log("Erro no método buscarTodos() da classe PedidosProvider. Erro: "+erro);
            return [];
        });
    }

    /**
     * Retorna o pedido que tem o ID informado
     * @param id_pedido
     */
    public async buscar(id_pedido: number): Promise<Pedido>{
        await this.getToken();
        return this.api.get('/pedidos/'+id_pedido).then((resposta) => {
            return resposta.data;
        }).catch((erro) => {
            console.log("Erro no método buscar() da classe PedidosProvider. Erro: "+erro);
            return null;
        });
    }
}