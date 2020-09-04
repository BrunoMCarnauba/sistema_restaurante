import Pedido from "./pedido";

/**
 * Classe Modelo com os dados lógicos das mesas
 * @class Mesa
 */
export default class Mesa {
    constructor(public status: string = 'Disponível', public pedido_id?:number, public pedido?: Pedido, public id_mesa?:number) {}
}