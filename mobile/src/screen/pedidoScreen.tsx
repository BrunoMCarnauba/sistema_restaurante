import * as React from 'react';
import { View, StyleSheet, Text, Alert, ScrollView, ActivityIndicator, FlatList } from 'react-native';
import { Button } from 'react-native-elements';
import { Toolbar } from '../components/toolbar';
import { BotaoLista } from '../components/botaoLista';
import { Fab } from '../components/floatingButton';
import { LoadingAnimation } from '../components/loading';
import Mesa from '../models/mesa';
import Pedido from '../models/pedido';
import ItemPedido from '../models/itemPedido';
import { PedidosProvider } from '../providers/pedidos';
import { MesasProvider } from '../providers/mesas';
import { ItensPedidosProvider } from '../providers/itensPedidos';

export interface AppProps {
    navigation: any;
    route: any;
}

export interface AppState {
  mesa: Mesa;
  itensPedido: ItemPedido[];
  loadingLista: boolean;
  loadingSalvar: boolean;
}

//Para testar enquanto tá sem banco de dados
const itensPedido = [
  {
    idItemPedido: 1,
    pedidoId: 1,
    quantidade: 3,
    preco: 21,
    comentario: '',
    produto: {
      idProduto: 1,
      nome: 'Sanduiche natural',
      descricao: 'Pão forma integral, patê de frango, alface, tomate e milho',
      categoria: {
        idCategoriaProduto: 2,
        nome: 'Lanches',
        descricao: 'Saudáveis ou fritos'
      },
      calorias: 120,
      preco: 7
    }
  },
  {
    idItemPedido: 2,
    pedidoId: 1,
    quantidade: 2,
    preco: 10,
    comentario: 'Sem açúcar',
    produto: {
      idProduto: 2,
      nome: 'Suco de laranja',
      descricao: 'Suco de laranja 150ml',
      categoria: {
        idCategoriaProduto: 3,
        nome: 'Bebidas',
        descricao: 'Sucos, Refrigerantes e Água'
      },
      calorias: 80,
      preco: 5
    }
  }
];

/**
 * Tela principal onde aparece os itens do pedido de determinada mesa
 */
export default class PedidoScreen extends React.Component<AppProps, AppState> {

  private pedidosProvider = new PedidosProvider();
  private mesasProvider = new MesasProvider();
  private itensPedidosProvider = new ItensPedidosProvider();

  constructor(props: AppProps) {
    super(props);
    this.state = {
      mesa: this.props.route.params?.mesa ?? new Mesa(),
      itensPedido: [],
      loadingLista: false,
      loadingSalvar: false
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
   * Carregando dados do banco de dados online (dados da mesa e pedidos dessa mesa (se houver))
   */
  private async carregarDados(){
    this.setState({loadingLista: true});

      this.setState({mesa: await this.mesasProvider.buscar(this.state.mesa.id_mesa)});  //Atualiza os dados da mesa
      if(this.state.mesa.pedido_id != undefined && this.state.mesa.pedido_id != 0){
        this.setState({itensPedido: await this.itensPedidosProvider.buscarTodos(this.state.mesa.pedido_id)}); //Carega os itens do pedido dessa mesa
      }

    this.setState({loadingLista: false});
  }

  /**
   * Função que Exclui um item da lista
   * @param id_item_pedido
   */
  public excluir(id_item_pedido) {
    Alert.alert('Excluir item', 'Deseja realmente excluir esse item?', [
      {text:'Sim', onPress:() => {
        
        //Exclui o item do pedido
        this.itensPedidosProvider.excluir(id_item_pedido).then(() => {
          //Atualiza a lista com os itens atuais
          this.itensPedidosProvider.buscarTodos(this.state.mesa.pedido_id).then(itensPedido => {
            this.setState({itensPedido});
            //Atualiza o objeto da mesa (pra o objeto pedido também ser atualizado e consequentemente poder atualizar o valor_total do pedido)
            this.mesasProvider.buscar(this.state.mesa.id_mesa).then((mesa) => {
              this.setState({mesa});
            });
          });
        });
      
      }},
      {text: 'Não'}
    ]);
  }

  /**
   * Função que finaliza o pedido e retorna o status da mesa para disponível.
   */
  public fecharConta(){
    Alert.alert('Fechar conta', 'Deseja realmente fechar o pedido?', [
      {text:'Sim', onPress:() => {
        let pedido = this.state.mesa.pedido;

        //Remove o pedido_id da mesa
        this.setState({mesa: {...this.state.mesa, pedido_id: null}});
        this.setState({mesa: {...this.state.mesa, pedido: null}});
        this.setState({mesa: {...this.state.mesa, status: "Disponível"}});
        this.mesasProvider.editar(this.state.mesa).then(() => {
          //Muda status do pedido
          pedido.status_ativo = false;
          this.pedidosProvider.editar(pedido).then(() => {
            this.props.navigation.navigate('MesasScreen');
          });
        });

      }},
      {text: 'Não'}
    ]);
  }

  /**
   * Função que exclui o pedido junto com seus itens e retorna o status da mesa para disponível.
   */
  public cancelarPedido(){
    Alert.alert('Excluir item', 'Deseja realmente excluir esse item?', [
      {text:'Sim', onPress:() => {
        this.setState({loadingSalvar: true});

        //Exclui o pedido
        this.pedidosProvider.excluir(this.state.mesa.pedido_id).then(() => {
          //Muda o status da mesa
          this.setState({mesa: {...this.state.mesa, pedido_id: null}});
          this.setState({mesa: {...this.state.mesa, pedido: null}});
          this.setState({mesa: {...this.state.mesa, status: "Disponível"}});
          this.mesasProvider.editar(this.state.mesa).then(() => {
            this.setState({loadingSalvar: false});
            this.props.navigation.navigate('MesasScreen');
          });
        });

      }},
      {text: 'Não'}
    ]);
  }

  /**
   * Função que inicia um pedido (se já não tiver sido iniciado) ou apenas redireciona para a tela de lista de produtos que podem ser adicionados no pedido
   */
  async adicionarItemPedido(){
    if(this.state.mesa.pedido_id != undefined && this.state.mesa.pedido_id != 0){
      this.props.navigation.navigate('ListaProdutosScreen', {mesa: this.state.mesa});
    }else{
      this.setState({loadingSalvar: true});

      //Inicia um pedido
      let pedido = new Pedido(true);
      let pedidoInserido = await this.pedidosProvider.cadastrar(pedido);
      //Atualiza o pedido_id da mesa
      this.setState({mesa: {...this.state.mesa, pedido: pedidoInserido}});
      this.setState({mesa: {...this.state.mesa, pedido_id: pedidoInserido.id_pedido}});
      this.setState({mesa: {...this.state.mesa, status: "Aguardando"}});
      await this.mesasProvider.editar(this.state.mesa);
      console.log(this.state.mesa);

      this.setState({loadingSalvar: false});

      this.props.navigation.navigate('ListaProdutosScreen', {mesa: this.state.mesa});
    }
  }

  public render() {
    return (
      <View style={{flex: 1, backgroundColor: '#212121'}}>
        <Toolbar titulo={"Pedido - Mesa 0"+this.state.mesa.id_mesa} navigation={this.props.navigation} back={true} marginBottom={0}></Toolbar>
        <LoadingAnimation statusLoading={this.state.loadingSalvar} />

        {/* Lista de itens do pedido */}
        <ScrollView contentContainerStyle={{flex: 1}} style={{paddingTop: 20, paddingBottom: 20}}>
          {this.state.loadingLista == true &&
            <ActivityIndicator animating={this.state.loadingLista} size={'large'} color={'#981e13'} style={{marginTop: 5}} />
          }

          {(this.state.loadingLista == false && this.state.itensPedido.length == 0) && <View style={styles.listaVaziaContainer}><Text style={styles.listaVazia}>Lista de itens vazia</Text></View>}

          {(this.state.itensPedido.length > 0) && 
            <FlatList data={this.state.itensPedido}  keyExtractor={(item) => item.id_item_pedido.toString()}
                      renderItem={({item}) => <BotaoLista nome={item.produto.nome} quantidade={item.quantidade} preco={item.preco} onExcluir={() => this.excluir(item.id_item_pedido)} onEditar={() => this.props.navigation.navigate('DetalheProdutoScreen', {itemPedido: item, produto: item.produto, mesa: this.state.mesa})}></BotaoLista>} />}

          {(this.state.mesa.pedido != null && this.state.mesa.pedido_id != null) && 
            <View style={{justifyContent: "flex-end", flex: 1, marginBottom: 55}}>
                <View style={{alignItems: 'center', marginTop: 40, marginLeft: 17, marginRight: 17}}>
                  <Text style={{fontSize: 18, color: 'white'}}>Total:</Text>
                  <Text style={{fontSize: 18, fontWeight: 'bold', color: 'white'}}>R$: {parseFloat(''+this.state.mesa.pedido.valor_total).toFixed(2)}</Text>
                </View>
                
                {(this.state.itensPedido.length > 0) && <Button title="Fechar conta" buttonStyle={styles.botaoFecharConta} onPress={() => this.fecharConta()} />}
                <Button title="Cancelar pedido" buttonStyle={styles.botaoFecharConta} onPress={() => this.cancelarPedido()} />
            </View>}
        </ScrollView>

        <Fab onPress={() => this.adicionarItemPedido()}/>   
      </View>
    );
  }
}


const styles = StyleSheet.create({
    botaoFecharConta: {
      backgroundColor: '#981e13',
      marginLeft: 15,
      marginRight: 15,
      marginTop: 10,
      borderRadius: 5
    },
    listaVaziaContainer:{
      marginTop: 20,
      alignItems: 'center'
    },
    listaVazia:{
      fontSize: 20,
      color: 'white',
    }
});