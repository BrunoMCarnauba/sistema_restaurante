import * as React from 'react';
import { View, StyleSheet, ScrollView, FlatList, Picker, Alert, ActivityIndicator } from 'react-native';
import { Button, Icon, SearchBar } from 'react-native-elements';
import { BotaoLista } from '../components/botaoLista';
import { Fab } from '../components/floatingButton';
import { Toolbar } from '../components/toolbar';
import CategoriaProduto from '../models/categoriaProduto';
import Produto from '../models/produto';
import Mesa from '../models/mesa';
import { ProdutosProvider } from '../providers/produtos';
import { CategoriasProdutosProvider } from '../providers/categoriasProdutos';

export interface AppProps {
    navigation: any;
    route: any;
}

export interface AppState {
  produtos: Produto[],
  mesa?: Mesa,
  categoriasProdutos: CategoriaProduto[],
  id_categoria_produto: number;
  buscaPalavra: string;
  filtroCategoria: boolean;
  loading: boolean;
}

//Para testar enquanto tá sem banco de dados
const produtos = [
    {
      idProduto: 1,
      nome: 'Sanduiche natural',
      descricao: 'Pão forma integral, patê de frango, alface, tomate e milho',
      categoria_id: 2,
        categoriaProduto: {
          id_categoria_produto: 2,
          nome: 'Lanches',
          descricao: 'Saudáveis e fritos'
        },
      calorias: 120,
      preco: 7,
      imagem: ''
    },
    {
      idProduto: 2,
      nome: 'Suco de laranja',
      descricao: 'Suco de laranja 150ml',
      categoria_id: 3,
        categoriaProduto: {
          id_categoria_produto: 3,
          nome: 'Bebidas',
          descricao: 'Sucos, Refrigerantes e Água'
        },
      calorias: 80,
      preco: 5
    }
  ];

{/* https://stackoverflow.com/questions/43016624/react-native-apply-array-values-from-state-as-picker-items */}
const categorias = [
  {
    id_categoria_produto: 1,
    nome: 'Todos',
    descricao: ''
  },
  {
    id_categoria_produto: 2,
    nome: 'Lanches',
    descricao: 'Saudáveis e fritos'
  },
  { 
    id_categoria_produto: 3,
    nome: 'Bebidas',
    descricao: 'Sucos, Refrigerantes e Água'
  },
  {
    id_categoria_produto: 4,
    nome: 'Almoço',
    descricao: ''
  },
  {
    id_categoria_produto: 5,
    nome: 'Jantar',
    descricao: ''
  },
]  

export default class ListaProdutosScreen extends React.Component<AppProps, AppState> {

  private produtosProvider = new ProdutosProvider();
  private categoriasProdutosProvider = new CategoriasProdutosProvider();

  constructor(props: AppProps) {
    super(props);
    this.state = {
      produtos: [],
      mesa: this.props.route.params?.mesa ?? undefined,
      categoriasProdutos: [],
      id_categoria_produto: 0,
      buscaPalavra: '',
      filtroCategoria: false,
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
   * Carrega a lista de produtos e as categorias, do banco de dados online
   */
  private async carregarDados(){
    this.setState({loading: true});

    this.setState({produtos: await this.produtosProvider.buscarTodos()});
    this.setState({categoriasProdutos: await this.categoriasProdutosProvider.buscarTodos()});
    
    this.setState({loading: false});
  }

  /**
   * Função que Exclui um item da lista
   * @param id_produto
   */
  public excluir(id_produto) {
    Alert.alert('Excluir produto', 'Deseja realmente excluir esse produto?', [
      {text:'Sim', onPress:() => {
        
        this.produtosProvider.excluir(id_produto).then(() => {
          this.produtosProvider.buscarTodos().then(produtos => {
            this.setState({produtos});
          });
        });
      
      }},
      {text: 'Não'}
    ]);
  }

  private aplicarFiltro(){
    console.log("Filtro por categoria de produto aplicado");
    this.setState({filtroCategoria: false});
  }

  public render() {
    return (
      <View style={{flex: 1, backgroundColor: '#212121'}}>
        {(this.state.mesa == undefined) && <Toolbar titulo="Lista de produtos" navigation={this.props.navigation} backTo='MenuScreen'/> }
        {(this.state.mesa != undefined) && <Toolbar titulo="Lista de produtos" navigation={this.props.navigation} back={true} />}

        {(this.state.filtroCategoria == false) && <Button title="Opções de filtragem" icon={<Icon name="search" type="font-awesome" color="white" containerStyle={{marginRight: 10}}/>} onPress={() => this.setState({filtroCategoria: true})} buttonStyle={{marginBottom: 10, ...styles.botoesFiltro}}></Button>}

        {(this.state.filtroCategoria == true) && <View style={styles.viewFiltro}>
          <View style={styles.viewPicker}>
            <Picker selectedValue={this.state.id_categoria_produto} onValueChange = {(id_categoria_produto, itemPosition) => this.setState({id_categoria_produto: id_categoria_produto})} style={styles.picker}>
              {this.state.categoriasProdutos.map((item, index) => {
                return (<Picker.Item label={item.nome} value={item.id_categoria_produto} color="#b0b0b0" />)
              })}
            </Picker>
          </View>

          <SearchBar lightTheme placeholder="Digite aqui..." onChangeText={(nome) => {this.setState({buscaPalavra: nome})}} value={this.state.buscaPalavra} placeholderTextColor='#b0b0b0' inputContainerStyle={{backgroundColor:'white', height: 39}} containerStyle={{borderRadius: 8, borderTopColor: 'transparent', borderBottomColor: 'transparent', marginLeft: 2, marginRight: 2}} />

          <Button title="Aplicar filtro" icon={<Icon name="search" type="font-awesome" color="white" containerStyle={{marginRight: 10}}/>} onPress={() => this.aplicarFiltro()} buttonStyle={{marginTop: 10, ...styles.botoesFiltro}}></Button>
        </View>}

        {/* Filtro versão 1 */}
        {/* <View style={{flexDirection: 'row', marginLeft: 7, marginRight: 7}}>
          <Button icon={<Icon name="format-list-bulleted-type" type="material-community" color="white"/>} onPress={() => this.setState({filtroCategoria: true})} buttonStyle={styles.botoesFiltro}></Button>
          <View style={{flex: 1}}>
            {(this.state.filtroCategoria == true) && 
            <View style={styles.viewPicker}>
              <Picker selectedValue={this.state.idCategoria} onValueChange = {(idCategoria, itemPosition) => this.aplicarCategoria(idCategoria)} style={styles.picker}>
                {categorias.map((item, index) => {
                  return (<Picker.Item label={item.nome} value={item.idCategoriaProduto} color="#b0b0b0" />)
                })}
              </Picker>
            </View>}
            {(this.state.filtroCategoria == false) && <SearchBar placeholder="Digite aqui..." onChangeText={(nome) => this.buscarNome(nome)} inputContainerStyle={{backgroundColor: 'white', height: 39}} />}
          </View>

          <Button icon={<Icon name="search" type="font-awesome" color="white"/>} onPress={() => this.setState({filtroCategoria: false})} buttonStyle={styles.botoesFiltro}></Button>
        </View> */}

        <ScrollView style={{paddingTop: 20}}>
            {/* Se tiver recebido o objeto da mesa */}
            {(this.state.mesa != undefined) && <FlatList data={this.state.produtos} keyExtractor={(item) => item.id_produto.toString()}
                      renderItem={({item}) => <BotaoLista nome={item.nome} preco={item.preco} onPress={() => this.props.navigation.navigate('DetalheProdutoScreen', {produto: item, mesa: this.state.mesa})}></BotaoLista>}/>
            }

            {/* Se não tiver recebido o objeto da mesa, então não é adição de item ao pedido */}
            {(this.state.mesa == undefined) && <FlatList data={this.state.produtos} keyExtractor={(item) => item.id_produto.toString()}
                      renderItem={({item}) => <BotaoLista nome={item.nome} preco={item.preco} onEditar={() => this.props.navigation.navigate('CadastroProdutoScreen', {produto: item})} onExcluir={() => this.excluir(item.id_produto)}></BotaoLista>}/>
            }

            {this.state.loading == true &&
              <ActivityIndicator animating={this.state.loading} size={'large'} color={'#981e13'} style={{marginTop: 5}} />
            }
        </ScrollView>
        
        {(this.state.mesa == undefined) &&
        <Fab onPress={() => this.props.navigation.navigate('CadastroProdutoScreen')}/>}
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
    }
});
