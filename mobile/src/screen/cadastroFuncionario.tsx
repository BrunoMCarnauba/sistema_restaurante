import * as React from 'react';
import { View, StyleSheet, Text, Picker, ScrollView } from 'react-native';
import { Input, Button } from 'react-native-elements';
import { Toolbar } from '../components/toolbar';
import estilosPadroes from '../styles';
import Funcionario from '../models/funcionario';
import Cargo from '../models/cargo';
import { FuncionariosProvider } from '../providers/funcionarios';
import { CargosProvider } from '../providers/cargos';
import { InputContainer } from '../components/inputContainer';
import { LoadingAnimation } from '../components/loading';

export interface AppProps {
  navigation: any;
  route: any;
}

export interface AppState {
    funcionario: Funcionario;
    cargos: Cargo[];
    emailValido: boolean;
    senhaValida: boolean;
    camposValidos: boolean;
    erroTecnico: boolean;
    loadingSalvar: boolean;
    loadingSelect: boolean;
}

export default class CadastroFuncionarioScreen extends React.Component<AppProps, AppState> {

  private funcionariosProvider = new FuncionariosProvider();
  private cargosProvider = new CargosProvider();

  constructor(props: AppProps) {
    super(props);
    this.state = this.getInitialState();
  }

  /**
   * Retorna o valor inicial do state
   * https://stackoverflow.com/questions/34845650/clearing-state-es6-react/34845925
   */
  private getInitialState(){
    return {
      funcionario: this.props.route.params?.funcionario ?? new Funcionario('', '', '', 0, new Cargo('', false)),
      cargos: [],
      emailValido: true,
      senhaValida: true,
      camposValidos: true,
      erroTecnico: false,
      loadingSalvar: false,
      loadingSelect: false
    }
  }

  /** Função chamada assim que a página é criada pela primeira vez */
  async componentDidMount() {
    //É adicionado um listener que é chamado sempre que a página é visitada
    this.props.navigation.addListener('focus', () => {
      this.setState({loadingSelect: true});
      this.cargosProvider.buscarTodos().then(cargos => {
        this.setState({cargos});
        this.setState({loadingSelect: false});
      });
    });
  }


  /**
   * Função que seta o cargo_id e o objeto cargo no model funcionario.
   */
  public setCargo(cargo: Cargo){
    this.setState({funcionario: {...this.state.funcionario, cargo: cargo}});
    this.setState({funcionario: {...this.state.funcionario, cargo_id: cargo.id_cargo}});
  }

  /**
   * Função que salva o funcionário (Insere se não existir o id_funcionario e edita se o id_funcionario já existir).
   */
  async salvar(){
    console.log("state camposValidos = "+this.state.camposValidos);
    //https://stackoverflow.com/questions/43370176/using-async-setstate
    await this.setState({loadingSalvar: true});
    await this.setState({camposValidos: true});
    await this.setState({emailValido: true});
    await this.setState({senhaValida: true});
    await this.setState({erroTecnico: false});

      //Validação de campos obrigatórios
      if(this.state.funcionario.nome == '' || this.state.funcionario.cargo_id == 0){
        await this.setState({camposValidos: false});
      }

      //Validação do email
      if(this.state.funcionario.email == "" || this.state.funcionario.email == null || this.state.funcionario.email.indexOf(".") == -1 || this.state.funcionario.email.indexOf("@") == -1){
        await this.setState({emailValido: false});
      }

      //validação da senha
      if(this.state.funcionario.senha == "" || this.state.funcionario.senha == null || this.state.funcionario.senha.length < 6){
        await this.setState({senhaValida: false});
      }

      //Salva no banco de dados se tiver tudo OK
      if(this.state.camposValidos == true && this.state.emailValido == true && this.state.senhaValida == true){
        if(this.state.funcionario.id_funcionario == undefined || this.state.funcionario.id_funcionario == 0){  //É um novo cadastro
          if(await this.funcionariosProvider.cadastrar(this.state.funcionario) == true){  //Tenta inserir no banco - Se conseguir, entra nessa condição.
            this.props.navigation.navigate('ListaFuncionariosScreen');
          }else{  //Se não tiver conseguido
            await this.setState({erroTecnico: true});
          }
        }else{  //É uma edição
          if(await this.funcionariosProvider.editar(this.state.funcionario) == true){
            this.props.navigation.navigate('ListaFuncionariosScreen');
          }else{
            await this.setState({erroTecnico: true});
          }
        }
        
        this.setState(this.getInitialState); //Reseta o state
      }

      await this.setState({loadingSalvar: false});
  }

  public render() {
    return (
        <View style={{flex: 1, backgroundColor: '#212121'}}>
            <Toolbar titulo="Cadastro de funcionário" navigation={this.props.navigation} back={true} loading={this.state.loadingSelect} />
            <LoadingAnimation statusLoading={this.state.loadingSalvar} />

            <ScrollView style={{}}>
                {/* Forma 1 pra atualizar objeto no state */}
                <Input label="Nome" placeholder="Digite o nome" containerStyle={styles.inputContainers} inputStyle={styles.inputStyles} value={this.state.funcionario.nome} onChangeText={(nome) => this.setState({funcionario: {...this.state.funcionario, nome: nome}})}/>
                {/* Forma 2: Usando prevState pra atualizar objeto no state  */}
                <Input label="Email" placeholder="Digite o email" containerStyle={styles.inputContainers} inputStyle={styles.inputStyles} keyboardType="email-address" autoCapitalize = 'none' value={this.state.funcionario.email} onChangeText={(email) => this.setState(prevState =>({funcionario: {...prevState.funcionario, email: email}}) )}/>
                {/* <Input label="Telefone celular" placeholder="Digite o telefone" containerStyle={styles.inputContainers} inputStyle={styles.inputStyles} keyboardType="phone-pad" onChangeText={()} /> */}
                <Input label="Senha" placeholder="Digite a senha" containerStyle={styles.inputContainers} inputStyle={styles.inputStyles} secureTextEntry={true} autoCapitalize = 'none' value={this.state.funcionario.senha} onChangeText={(senha) => this.setState({funcionario: {...this.state.funcionario, senha: senha}})} />
                
                <InputContainer label={"Cargo"} containerStyle={{...styles.inputContainers}}>
                  <Picker selectedValue={this.state.funcionario.cargo_id} 
                          onValueChange = {(itemValue, itemIndex) => this.setState({funcionario: {...this.state.funcionario, cargo_id: itemValue}})}
                          style={{color: '#c6c6c4'}}>
                      <Picker.Item label="Selecione um cargo" value={0} color="black" />
                      {this.state.cargos.map((item, index) => {
                          return (<Picker.Item label={item.nome} value={item.id_cargo} key={index} color="black" />) 
                      })}
                  </Picker>
                </InputContainer>

                {(this.state.emailValido == false || this.state.senhaValida == false || this.state.camposValidos == false || this.state.erroTecnico == true) && <View style={estilosPadroes.retanguloErro}>
                  {this.state.camposValidos == false && <Text style={estilosPadroes.textoErro}>Por favor, preencha todos os campos.</Text>}
                  {this.state.emailValido == false && <Text style={estilosPadroes.textoErro}>Digite um email válido!</Text>}
                  {this.state.senhaValida == false && <Text style={estilosPadroes.textoErro}>Digite uma senha com no mínimo 6 caracteres!</Text>}
                  {this.state.erroTecnico == true && <Text style={estilosPadroes.textoErro}>Houve um erro ao tentar salvar seu cadastro. Talvez o e-mail já exista.</Text>}
                </View>}

                <Button title="Salvar" buttonStyle={{borderRadius: 20, backgroundColor: '#981e13', marginLeft: 50, marginRight: 50, marginTop: 20}} onPress={() => this.salvar()} />
            </ScrollView>
        </View>
    );
  }
}

const styles = StyleSheet.create({
    inputContainers: {
        marginBottom: 15
    },
    inputStyles:{
      color: '#c6c6c4'
    }
});