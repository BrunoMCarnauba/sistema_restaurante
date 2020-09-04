/**
 * Classe Modelo com os dados lógicos das categorias dos produtos
 * @class CategoriaProduto
 */
export default class CategoriaProduto {
    constructor(public nome:string, public descricao: string = '', public id_categoria_produto?:number) {}
}