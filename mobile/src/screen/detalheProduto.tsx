import * as React from 'react';
import { View, StyleSheet, Text, ScrollView } from 'react-native';
import { Image, Input, Button } from 'react-native-elements';
import { Toolbar } from '../components/toolbar';
import { LoadingAnimation } from '../components/loading';
import NumericInput from 'react-native-numeric-input'
import Produto from '../models/produto';
import ItemPedido from '../models/itemPedido';
import CategoriaProduto from '../models/categoriaProduto';
import Mesa from '../models/mesa';
import Pedido from '../models/pedido';
import { PedidosProvider } from '../providers/pedidos';
import { ItensPedidosProvider } from '../providers/itensPedidos';
import { ProdutosProvider } from '../providers/produtos';

export interface AppProps {
  navigation: any;
  route: any;
}

export interface AppState {
  produto: Produto;
  mesa: Mesa,
  itemPedido: ItemPedido;
  camposValidos: boolean;
  erroTecnico: boolean;
  loadingSalvar: boolean;
  loadingImagem: boolean;
}


export default class DetalheProdutoScreen extends React.Component<AppProps, AppState> {

  private itensPedidosProvider = new ItensPedidosProvider();
  private produtosProvider = new ProdutosProvider();

  constructor(props: AppProps) {
    super(props);
    // let produto = new Produto(1, 'Sanduiche natural', 'Pão forma integral, patê de frango, alface, tomate e milho', {idCategoriaProduto: 2, nome: 'Lanches', descricao: 'Saudáveis ou fritos'}, 120, 7, '');
    this.state = {
      produto: this.props.route.params?.produto ?? new Produto({}),
      mesa: this.props.route.params?.mesa ?? undefined,
      itemPedido: this.props.route.params?.itemPedido ?? new ItemPedido(0, 0, new Produto({}), 1, 0.00, false),
      camposValidos: true,
      erroTecnico: false,
      loadingSalvar: false,
      loadingImagem: false
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
   * Carrega o produto do banco de dados, para que seja atualizado o atributo imagem
   */
  private async carregarDados(){
    this.setState({loadingImagem: true});

    if(this.state.produto.id_produto != undefined){
      console.log(this.state.produto.id_produto);
      let imagemProduto = (await this.produtosProvider.buscar(this.state.produto.id_produto)).imagem;
      this.setState({produto: {...this.state.produto, imagem: imagemProduto}});
    }

    this.setState({loadingImagem: false});
  }

  async adicionarAoPedido(){
    //https://stackoverflow.com/questions/43370176/using-async-setstate
    await this.setState({loadingSalvar: true});
    await this.setState({camposValidos: true});
    await this.setState({erroTecnico: false});

      //Validação de campos obrigatórios
      if(this.state.itemPedido.quantidade < 1){
        await this.setState({camposValidos: false});
      }

      //Salva no banco de dados se tiver tudo OK
      if(this.state.camposValidos == true){
        if(this.state.itemPedido.id_item_pedido == undefined || this.state.itemPedido.id_item_pedido == 0){  //É um novo cadastro
          this.setState({itemPedido: {...this.state.itemPedido, produto_id: this.state.produto.id_produto}});
          this.setState({itemPedido: {...this.state.itemPedido, produto: this.state.produto}});
          this.setState({itemPedido: {...this.state.itemPedido, pedido_id: this.state.mesa.pedido_id}});
          this.setState({itemPedido: {...this.state.itemPedido, status_pronto: false}});
          if(await this.itensPedidosProvider.cadastrar(this.state.itemPedido) == true){  //Tenta inserir no banco - Se conseguir, entra nessa condição.
            this.props.navigation.navigate('PedidoScreen', {mesa: this.state.mesa});
          }else{  //Se não tiver conseguido
            await this.setState({erroTecnico: true});
          }
        }else{  //É uma edição
          if(await this.itensPedidosProvider.editar(this.state.itemPedido) == true){
            this.props.navigation.navigate('PedidoScreen', {mesa: this.state.mesa});
          }else{
            await this.setState({erroTecnico: true});
          }
        }
      }

      await this.setState({loadingSalvar: false});
  }

  public render() {
    return (
      <View style={{flex: 1, backgroundColor: '#212121'}}>
        <Toolbar titulo={this.state.produto.nome} navigation={this.props.navigation} back={true} marginBottom={0} loading={this.state.loadingImagem}></Toolbar>
        <LoadingAnimation statusLoading={this.state.loadingSalvar} />

        <ScrollView>
          <View style={styles.imagemContainer}>
            {/* Se a imagem do produto existir, então exibe ela. Se não existir, exibe a imagem sem-foto.jpg */}
            <Image source={(this.state.produto.imagem ? {uri: this.state.produto.imagem} : require('./../../assets/imgs/sem-foto.jpg'))} style={styles.imagem} borderRadius={8} />
          </View>

          <View style={styles.viewTexto}>
            <Text style={{...styles.textos, fontSize: 20,}}>{this.state.produto.descricao}</Text>
            <Text style={{...styles.textos, fontSize: 18}}>Calorias: {this.state.produto.calorias}</Text>
            <Text style={{...styles.textos, fontSize: 18}}>Preço da unidade: R$: {parseFloat(''+this.state.produto.preco).toFixed(2)}</Text>
          </View>

          <Input label='Comentário' value={this.state.itemPedido.comentario} onChangeText={(comentario) => {this.setState({itemPedido: {...this.state.itemPedido, comentario}})}} inputContainerStyle={styles.inputContainers} inputStyle={styles.inputStyles} />

          <View style= {{alignItems: 'center',}}>
            <NumericInput value={this.state.itemPedido.quantidade} onChange={quantidade => {this.setState({itemPedido: {...this.state.itemPedido, quantidade}})}} minValue={1} rightButtonBackgroundColor='#981e13' leftButtonBackgroundColor='#981e13' iconStyle={{ color: 'white' }} containerStyle={{marginTop: 35, marginBottom: 10}} inputStyle={{backgroundColor: 'white'}} />
          </View>

          <View style={{marginTop: 20, alignItems: 'center', marginLeft: 17, marginRight: 17}}>
              <Text style={{fontSize: 18, color: 'white'}}>Total: </Text>
              <Text style={{fontSize: 18, fontWeight: 'bold', color: 'white'}}>R$: {(this.state.produto.preco * this.state.itemPedido.quantidade).toFixed(2)}</Text>
          </View>
          <Button title="Adicionar ao pedido" buttonStyle={{borderRadius: 20, backgroundColor: '#981e13', marginLeft: 50, marginRight: 50, marginTop: 10, marginBottom: 80}} onPress={() => this.adicionarAoPedido()}/>
        </ScrollView>
      </View>
    );
  }
}

const styles = StyleSheet.create({
  imagemContainer:{
    marginTop: 10,
    marginBottom: 5,
    marginLeft: 10,
    marginRight: 10,
    backgroundColor: 'rgba(255,255,255,0.3)',
    alignItems: 'center',
    borderBottomWidth: 0.5,
    borderTopWidth: 0.5,
    borderColor: '#839099'
  },
  imagem:{
    height: 200,
    width: 200,
    borderColor: '#981e13',
    borderWidth: 8,
    borderRadius: 5,
  },
  inputContainers: {
    marginBottom: 5
  },
  inputStyles: {
    color: '#c6c6c4'
  },
  viewTexto:{
    marginLeft: 10,
    marginRight: 10,
    marginTop: 5,
    marginBottom: 10
  },
  textos:{
    color: 'rgba(255,255,255,0.9)',
    marginBottom: 5
  },
});
