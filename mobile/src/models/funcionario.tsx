import Cargo from "./cargo";

/**
 * Classe Modelo com os dados lógicos dos funcionários
 * @class Funcionario
 */
export default class Funcionario {
    constructor(public nome:string, public email:string, public senha:string, public cargo_id: number, public cargo: Cargo, public id_funcionario?:number) {}
}