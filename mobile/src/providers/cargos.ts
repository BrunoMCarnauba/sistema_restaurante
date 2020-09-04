import { number } from 'prop-types';
import { APIProviders } from './api';
import Cargo from '../models/cargo';

export class CargosProvider extends APIProviders{

    /**
     * Cadastra um novo cargo
     * @param cargo
     */
    public async cadastrar(cargo: Cargo): Promise<boolean>{ //Como é uma função async, então ela retorna os dados como uma Promise
        await this.getToken();  //Autentica o usuário
        return this.api.post('/cargos', {cargo: cargo}).then((resposta) => {
            return true;
        }).catch((erro) => {
            console.log("Erro no método cadastrar() da classe CargosProvider. Erro: "+erro);
            return false;
        });
    }

    /**
     * Edita o registro de um cargo
     * @param cargo
     */
    public async editar(cargo: Cargo): Promise<boolean>{
        await this.getToken();
        return this.api.put('/cargos/'+cargo.id_cargo, {cargo: cargo}).then((resposta) => {
            return true;
        }).catch((erro) => {
            console.log("Erro no método editar() da classe CargosProvider. Erro: "+erro);
            return false;
        });
    }

    /**
     * Exclui o registro de um cargo
     * @param id_cargo
     */
    public async excluir(id_cargo: number): Promise<boolean>{
        await this.getToken();
        return this.api.delete('/cargos/'+id_cargo).then((resposta) => {
            return true;
        }).catch((erro) => {
            console.log("Erro no método excluir() da classe CargosProvider. Erro: "+erro);
            return false;
        });
    }

    /**
     * Busca todos os cargos. Retorna um vetor com todos eles.
     */
    public async buscarTodos(): Promise<Cargo[]>{
        await this.getToken();
        return this.api.get('/cargos').then((resposta) => {
            return resposta.data;
        }).catch((erro) => {
            console.log("Erro no método buscarTodos() da classe CargosProvider. Erro: "+erro);
            return [];
        });
    }

    /**
     * Retorna o cargo com ID informado
     * @param id_cargo
     */
    public async buscar(id_cargo: number): Promise<Cargo>{
        await this.getToken();
        return this.api.get('/cargos/'+id_cargo).then((resposta) => {
            return resposta.data;
        }).catch((erro) => {
            console.log("Erro no método buscar() da classe CargosProvider. Erro: "+erro);
            return null;
        })
    }
}