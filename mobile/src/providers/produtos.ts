import { number } from 'prop-types';
import { APIProviders } from './api';
import Produto from '../models/produto';

export class ProdutosProvider extends APIProviders{

    /**
     * Cadastra um novo produto
     * @param produto
     */
    public async cadastrar(produto: Produto): Promise<boolean>{ //Como é uma função async, então ela retorna os dados como uma Promise
        await this.getToken();  //Autentica o usuário
        return this.api.post('/produtos', {produto: produto}).then((resposta) => {
            return true;
        }).catch((erro) => {
            console.log("Erro no método cadastrar() da classe ProdutosProvider. Erro: "+erro);
            return false;
        });
    }

    /**
     * Edita o registro de um produto
     * @param produto
     */
    public async editar(produto: Produto): Promise<boolean>{
        await this.getToken();
        return this.api.put('/produtos/'+produto.id_produto, {produto: produto}).then((resposta) => {
            return true;
        }).catch((erro) => {
            console.log("Erro no método editar() da classe ProdutosProvider. Erro: "+erro);
            return false;
        });
    }

    /**
     * Exclui o registro de um produto
     * @param id_produto
     */
    public async excluir(id_produto: number): Promise<boolean>{
        await this.getToken();
        return this.api.delete('/produtos/'+id_produto).then((resposta) => {
            return true;
        }).catch((erro) => {
            console.log("Erro no método excluir() da classe ProdutosProvider. Erro: "+erro);
            return false;
        });
    }

    /**
     * Busca todos os produtos. Retorna um vetor com todos eles.
     */
    public async buscarTodos(): Promise<Produto[]>{
        await this.getToken();
        return this.api.get('/produtos').then((resposta) => {
            return resposta.data;
        }).catch((erro) => {
            console.log("Erro no método buscarTodos() da classe ProdutosProvider. Erro: "+erro);
            return [];
        });
    }

    /**
     * Retorna o produto que tem o ID informado
     * @param id_produto
     */
    public async buscar(id_produto: number): Promise<Produto>{
        await this.getToken();
        return this.api.get('/produtos/'+id_produto).then((resposta) => {
            return resposta.data;
        }).catch((erro) => {
            console.log("Erro no método buscar() da classe ProdutosProvider. Erro: "+erro);
            return null;
        });
    }
}