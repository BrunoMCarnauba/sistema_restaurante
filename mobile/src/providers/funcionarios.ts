import { APIProviders } from './api';
import { AsyncStorage } from 'react-native';
import Funcionario from '../models/funcionario';

export class FuncionariosProvider extends APIProviders{

    /**
     * Efetua login (retorna os dados do funcionário caso encontre e retorna null se não encontrá-lo.)
     * Para acessar o token gerado usa AsyncStorage.getItem('token');
     */
    public autenticar(email: string, senha: string): Promise<Funcionario>{
        return this.api.post('/login', {email: email, senha: senha}).then((resposta) => {
            AsyncStorage.setItem('token', resposta.data.jwt); //Salva o token gerado no login para poder usá-lo nas próximas páginas
            return resposta.data.funcionario; //Retorna o funcionário
        }).catch((erro) => {
            console.log("Erro no método autenticar() da classe FuncionariosProvider. Erro: "+erro);
            return null;
        });
    }

    /**
     * Informa se o usuário está logado ou não
     */
    public estaLogado(): boolean{
        if(AsyncStorage.getItem('token') != null){  //Usuário logado
            return true; 
        }else{
            return false;
        }
    }

    /**
     * Desloga o funcionário removendo seu token
     */
    public logout(){
        AsyncStorage.removeItem('token');
    }

    /**
     * Cadastra um novo funcionário
     * @param funcionario 
     */
    public async cadastrar(funcionario: Funcionario): Promise<boolean>{ //Como é uma função async, então ela retorna os dados como uma Promise
        // console.log(JSON.stringify({funcionario: funcionario}));  return false;  //Para teste
        await this.getToken();  //Autentica o usuário
        return this.api.post('/funcionarios', {funcionario: funcionario}).then((resposta) => {
            return true;
        }).catch((erro) => {
            console.log("Erro no método cadastrar() da classe FuncionariosProvider. Erro: "+erro);
            return false;
        });
    }

    /**
     * Edita o registro de um funcionário
     * @param funcionario
     */
    public async editar(funcionario: Funcionario): Promise<boolean>{
        await this.getToken();
        return this.api.put('/funcionarios/'+funcionario.id_funcionario, {funcionario: funcionario}).then((resposta) => {
            return true;
        }).catch((erro) => {
            console.log("Erro no método editar() da classe FuncionariosProvider. Erro: "+erro);
            return false;
        });
    }

    /**
     * Exclui o registro de um funcionário
     * @param id_funcionario
     */
    public async excluir(id_funcionario: number): Promise<boolean>{
        await this.getToken();
        return this.api.delete('/funcionarios/'+id_funcionario).then((resposta) => {
            return true;
        }).catch((erro) => {
            console.log("Erro no método excluir() da classe FuncionariosProvider. Erro: "+erro);
            return false;
        });
    }

    /**
     * Busca todos os funcionários. Retorna um vetor com todos eles.
     */
    public async buscarTodos(): Promise<Funcionario[]>{
        await this.getToken();
        return this.api.get('/funcionarios').then((resposta) => {
            return resposta.data;
        }).catch((erro) => {
            console.log("Erro no método buscarTodos() da classe FuncionariosProvider. Erro: "+erro);
            return [];
        });
    }

    /**
     * Retorna o funcionário com ID informado
     * @param id_funcionario
     */
    public async buscar(id_funcionario: number): Promise<Funcionario>{
        await this.getToken();
        return this.api.get('/funcionarios/'+id_funcionario).then((resposta) => {
            return resposta.data;
        }).catch((erro) => {
            console.log("Erro no método buscar() da classe FuncionariosProvider. Erro: "+erro);
            return null;
        })
    }
}