import * as React from 'react';
import { View, StyleSheet, ScrollView, FlatList, Picker, Alert, ActivityIndicator } from 'react-native';
import { Button, Icon, SearchBar } from 'react-native-elements';
import { BotaoLista } from '../components/botaoLista';
import { Fab } from '../components/floatingButton';
import { Toolbar } from '../components/toolbar';
import Funcionario from '../models/funcionario';
import Cargo from '../models/cargo';
import { FuncionariosProvider } from '../providers/funcionarios';
import { CargosProvider } from '../providers/cargos';

export interface AppProps {
    navigation: any;
    funcionarios: Funcionario[];
    cargos: Cargo[];
}

export interface AppState {
  funcionarios: Funcionario[];
  cargos: Cargo[];
  id_cargo: number;
  buscaPalavra: string;
  filtroCargo: boolean;
  loading: boolean;
}

//Para testar enquanto tá sem banco de dados
const funcionarios = [
    {
      id_funcionario: 1,
      nome: 'Fulano de tal',
      email: 'teste@teste.com',
      cargo_id: 1,
      cargo: {
        id_cargo: 1,
        nome: 'Administrador',
        tudo_permitido: true,
        descricao: 'Tem acesso a todo o sistema'
      },
      senha: '123456',
    },
    {
      id_funcionario: 2,
      nome: 'Fulano dois',
      email: 'testedois@gmail.com',
      cargo_id: 1,
      cargo: {
        id_cargo: 1,
        nome: 'Administrador',
        tudo_permitido: true,
        descricao: 'Tem acesso a todo o sistema'
      },
      senha: '123456'
    }
  ];

{/* https://stackoverflow.com/questions/43016624/react-native-apply-array-values-from-state-as-picker-items */}
const cargos = [
  {
    id_cargo: 1,
    nome: 'Administrador',
    descricao: '',
    tudo_permitido: true
  },
  {
    id_cargo: 2,
    nome: 'Garçom',
    descricao: '',
    tudo_permitido: false
  },
  { 
    id_cargo: 3,
    nome: 'Cozinheiro',
    descricao: 'S',
    tudo_permitido: false
  }
]  

export default class ListaFuncionariosScreen extends React.Component<AppProps, AppState> {

  private funcionariosProvider = new FuncionariosProvider();
  private cargosProvider = new CargosProvider();

  constructor(props: AppProps) {
    super(props);
    this.state = {
      funcionarios: this.props.funcionarios,
      cargos: this.props.cargos,
      id_cargo: 0,
      buscaPalavra: '',
      filtroCargo: false,
      loading: false
    };
  }

  /** Função chamada assim que a página é criada pela primeira vez */
  async componentDidMount() {
    //É adicionado um listener que é chamado sempre que a página é visitada
    this.props.navigation.addListener('focus', () => {
      this.carregarDados();
    });
  }

  /**
   * Carrega a lista de funcionários e a lista de cargos do banco de dados online
   */
  private async carregarDados(){
    this.setState({loading: true});

    this.setState({funcionarios: await this.funcionariosProvider.buscarTodos()});
    this.setState({cargos: await this.cargosProvider.buscarTodos()});
    
    this.setState({loading: false});
  }

  private aplicarFiltro(){
    console.log("Filtro por cargo aplicado");
    this.setState({filtroCargo: false});
  }

  /**
   * Função que Exclui um item da lista
   * @param id_funcionario
   */
  public excluir(id_funcionario) {
    if(this.state.funcionarios.length == 1){
      Alert.alert('Erro', 'Você não pode excluir o último registro de funcionário que resta. Ao invés disso, você pode editá-lo.');
    }else{
      Alert.alert('Excluir Funcionário', 'Deseja realmente excluir esse funcionário?', [
        {text:'Sim', onPress:() => {
          
          this.funcionariosProvider.excluir(id_funcionario).then(() => {
            this.funcionariosProvider.buscarTodos().then(funcionarios => {
              this.setState({funcionarios})
            });
          });

        }},
        {text: 'Não'}
      ]);
    }
  }

  public render() {
    return (
      <View style={{flex: 1, backgroundColor: '#212121'}}>
        <Toolbar titulo="Lista de funcionários" navigation={this.props.navigation} backTo='MenuScreen' />

        {(this.state.filtroCargo == false) && <Button title="Opções de filtragem" icon={<Icon name="search" type="font-awesome" color="white" containerStyle={{marginRight: 10}}/>} onPress={() => this.setState({filtroCargo: true})} buttonStyle={{marginBottom: 10, ...styles.botoesFiltro}}></Button>}

        {(this.state.filtroCargo == true) && <View style={styles.viewFiltro}>
          <View style={styles.viewPicker}>
            <Picker selectedValue={this.state.id_cargo} onValueChange = {(idCargo, itemPosition) => this.setState({id_cargo: idCargo})} style={styles.picker}>
              {this.state.cargos.map((item, index) => {
                return (<Picker.Item label={item.nome} value={item.id_cargo} color="#b0b0b0" />)
              })}
            </Picker>
          </View>

          <SearchBar lightTheme placeholder="Digite aqui..." onChangeText={(nome) => {this.setState({buscaPalavra: nome})}} value={this.state.buscaPalavra} placeholderTextColor='#b0b0b0' inputContainerStyle={{backgroundColor:'white', height: 39}} containerStyle={{borderRadius: 8, borderTopColor: 'transparent', borderBottomColor: 'transparent', marginLeft: 2, marginRight: 2}} />

          <Button title="Aplicar filtro" icon={<Icon name="search" type="font-awesome" color="white" containerStyle={{marginRight: 10}}/>} onPress={() => this.aplicarFiltro()} buttonStyle={{marginTop: 10, ...styles.botoesFiltro}}></Button>
        </View>}

        <ScrollView style={{paddingTop: 20}}>
            <FlatList data={this.state.funcionarios} keyExtractor={(item) => item.id_funcionario.toString()}
                      renderItem={({item}) => <BotaoLista nome={item.nome} onEditar={() => this.props.navigation.navigate('CadastroFuncionarioScreen', {funcionario: item})} onExcluir={() => this.excluir(item.id_funcionario)}></BotaoLista>}/>

            {this.state.loading == true &&
              <ActivityIndicator animating={this.state.loading} size={'large'} color={'#981e13'} style={{marginTop: 5}} />
            }
        </ScrollView>

        <Fab onPress={() => this.props.navigation.navigate('CadastroFuncionarioScreen')}/>   
      </View>
    );
  }
}

const styles = StyleSheet.create({
    botoesFiltro: {
      backgroundColor: '#981e13',
      // height: 56,
      // width: 50,
      borderRadius: 20,
      marginLeft: 50,
      marginRight: 50,
    },
    viewFiltro:{
      borderRadius: 10,
      borderColor: 'rgba(255,255,255, 0.4)',
      borderWidth: 1,
      backgroundColor: 'rgba(152,30,19, 0.5)',
      paddingTop: 8,
      paddingBottom: 8,
      paddingLeft: 10,
      paddingRight: 10,
      marginLeft: 30,
      marginRight: 30,
      marginBottom: 9
    },
    viewPicker:{
      backgroundColor: '#dae2ea',
      height: 56,
      marginBottom: 5,
      borderRadius: 5
    },
    picker:{
      backgroundColor: 'white',
      height: 40,
      borderRadius: 5,
      marginTop: 8,
      marginLeft: 8,
      marginRight: 8
    },
    lista:{
      paddingTop: 20,
      justifyContent: 'center',
    }
});
