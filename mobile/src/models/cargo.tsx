/**
 * Classe Modelo com os dados lógicos dos cargos
 * @class Cargo
 */
export default class Cargo {
    constructor(public nome:string, public tudo_permitido: boolean, public descricao:string = '', public salario:number = 0, public id_cargo?:number) {}
}