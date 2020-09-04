/**
 * Classe Modelo com os dados lógicos dos pedidos
 * @class Pedido
 */
export default class Pedido {
    constructor(public status_ativo: boolean = true, public valor_total: number = 0.00, public id_pedido?:number) {}
}