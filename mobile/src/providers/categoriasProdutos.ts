import { number } from 'prop-types';
import { APIProviders } from './api';
import CategoriaProduto from '../models/categoriaProduto';

export class CategoriasProdutosProvider extends APIProviders{

    /**
     * Cadastra uma nova categoria de produto
     * @param categoriaProduto
     */
    public async cadastrar(categoriaProduto: CategoriaProduto): Promise<boolean>{ //Como é uma função async, então ela retorna os dados como uma Promise
        await this.getToken();  //Autentica o usuário
        return this.api.post('/categoriasprodutos', {categoriaProduto: categoriaProduto}).then((resposta) => {
            return true;
        }).catch((erro) => {
            console.log("Erro no método cadastrar() da classe CategoriasProdutosProvider. Erro: "+erro);
            return false;
        });
    }

    /**
     * Edita o registro de uma categoria de produto
     * @param categoriaProduto
     */
    public async editar(categoriaProduto: CategoriaProduto): Promise<boolean>{
        await this.getToken();
        return this.api.put('/categoriasprodutos/'+categoriaProduto.id_categoria_produto, {categoriaProduto: categoriaProduto}).then((resposta) => {
            return true;
        }).catch((erro) => {
            console.log("Erro no método editar() da classe CategoriasProdutosProvider. Erro: "+erro);
            return false;
        });
    }

    /**
     * Exclui o registro de uma categoria de produto
     * @param id_cargo
     */
    public async excluir(id_categoria_produto: number): Promise<boolean>{
        await this.getToken();
        return this.api.delete('/categoriasprodutos/'+id_categoria_produto).then((resposta) => {
            return true;
        }).catch((erro) => {
            console.log("Erro no método excluir() da classe CategoriasProdutosProvider. Erro: "+erro);
            return false;
        });
    }

    /**
     * Busca todos as categorias de produtos. Retorna um vetor com todos eles.
     */
    public async buscarTodos(): Promise<CategoriaProduto[]>{
        await this.getToken();
        return this.api.get('/categoriasprodutos').then((resposta) => {
            return resposta.data;
        }).catch((erro) => {
            console.log("Erro no método buscarTodos() da classe CategoriasProdutosProvider. Erro: "+erro);
            return [];
        });
    }

    /**
     * Retorna a categoria de produto que tem o ID informado
     * @param id_categoria_produto
     */
    public async buscar(id_categoria_produto: number): Promise<CategoriaProduto>{
        await this.getToken();
        return this.api.get('/categoriasprodutos/'+id_categoria_produto).then((resposta) => {
            return resposta.data;
        }).catch((erro) => {
            console.log("Erro no método buscar() da classe CategoriasProdutosProvider. Erro: "+erro);
            return null;
        })
    }
}