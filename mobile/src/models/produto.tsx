import CategoriaProduto from "./categoriaProduto";

/**
 * Classe Modelo com os dados lógicos dos produtos
 * @class Produto
 */
export default class Produto {
    public id_produto?: number;
    public nome?: string;
    public categoria_id?: number;
    public categoriaProduto?: CategoriaProduto;
    public preco?: number;
    public descricao?: string = "";
    public calorias?: number = 0;
    public imagem?: string;

    //Construtor usando named parameters
    constructor({id_produto, nome, categoria_id, categoriaProduto, preco, descricao = '', calorias = 0, imagem}:
          {id_produto?: number, nome?: string, categoria_id?: number, categoriaProduto?: CategoriaProduto, preco?: number, descricao?: string, calorias?: number, imagem?: string}) {
        this.id_produto = id_produto;
        this.nome = nome;
        this.categoria_id = categoria_id;
        this.categoriaProduto = categoriaProduto;
        this.preco = preco;
        this.descricao = descricao;
        this.calorias = calorias;
        this.imagem = imagem;
    }

}