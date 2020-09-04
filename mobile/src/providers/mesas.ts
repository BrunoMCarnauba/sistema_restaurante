import { number } from 'prop-types';
import { APIProviders } from './api';
import Mesa from '../models/mesa';

export class MesasProvider extends APIProviders{

    /**
     * Cadastra uma nova mesa
     * @param mesa
     */
    public async cadastrar(mesa: Mesa): Promise<boolean>{ //Como é uma função async, então ela retorna os dados como uma Promise
        await this.getToken();  //Autentica o usuário
        return this.api.post('/mesas', {mesa: mesa}).then((resposta) => {
            return true;
        }).catch((erro) => {
            console.log("Erro no método cadastrar() da classe MesasProvider. Erro: "+erro);
            return false;
        });
    }

    /**
     * Edita o registro de uma mesa
     * @param produto
     */
    public async editar(mesa: Mesa): Promise<boolean>{
        await this.getToken();
        return this.api.put('/mesas/'+mesa.id_mesa, {mesa: mesa}).then((resposta) => {
            return true;
        }).catch((erro) => {
            console.log("Erro no método editar() da classe MesasProvider. Erro: "+erro);
            return false;
        });
    }

    /**
     * Exclui o registro de uma mesa
     * @param id_mesa
     */
    public async excluir(id_mesa: number): Promise<boolean>{
        await this.getToken();
        return this.api.delete('/mesas/'+id_mesa).then((resposta) => {
            return true;
        }).catch((erro) => {
            console.log("Erro no método excluir() da classe MesasProvider. Erro: "+erro);
            return false;
        });
    }

    /**
     * Busca todas as mesas. Retorna um vetor com todas elas.
     */
    public async buscarTodos(): Promise<Mesa[]>{
        await this.getToken();
        return this.api.get('/mesas').then((resposta) => {
            return resposta.data;
        }).catch((erro) => {
            console.log("Erro no método buscarTodos() da classe MesasProvider. Erro: "+erro);
            return [];
        });
    }

    /**
     * Retorna a mesa que tem o ID informado
     * @param id_mesa
     */
    public async buscar(id_mesa: number): Promise<Mesa>{
        await this.getToken();
        return this.api.get('/mesas/'+id_mesa).then((resposta) => {
            return resposta.data;
        }).catch((erro) => {
            console.log("Erro no método buscar() da classe MesasProvider. Erro: "+erro);
            return null;
        });
    }
}