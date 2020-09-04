import Produto from "./produto";

/**
 * Classe Modelo com os dados lógicos dos items do pedido
 * @class ItemPedido
 */
export default class ItemPedido {
    constructor(public pedido_id: number, public produto_id: number, public produto: Produto, public quantidade: number, public preco: number,  public status_pronto: boolean, public comentario: string = '', public id_item_pedido?:number) {}
}