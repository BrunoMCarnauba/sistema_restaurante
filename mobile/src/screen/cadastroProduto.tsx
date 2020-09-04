import * as React from 'react';
import { View, StyleSheet, Text, ImageBackground, Picker, TouchableOpacity, ScrollView } from 'react-native';
import { Input, Image, Button } from 'react-native-elements';
import { Toolbar } from '../components/toolbar';
import estilosPadroes from '../styles';
import Produto from '../models/produto';
import * as Permissions from 'expo-permissions';
import * as ImagePicker from 'expo-image-picker';
import { ProdutosProvider } from '../providers/produtos';
import { CategoriasProdutosProvider } from '../providers/categoriasProdutos';
import CategoriaProduto from '../models/categoriaProduto';
import { InputContainer } from '../components/inputContainer';
import { LoadingAnimation } from '../components/loading';

export interface AppProps {
    navigation: any;
    route: any;
}

export interface AppState {
    produto: Produto;   //https://stackoverflow.com/questions/43638938/updating-an-object-with-setstate-in-react
    categoriasProdutos: CategoriaProduto[];
    preco: string;
    camposValidos: boolean;
    erroTecnico: boolean;
    loadingSalvar: boolean;
    loadingSecundario: boolean;
}

export default class CadastroProdutoScreen extends React.Component<AppProps, AppState> {

  private produtosProvider = new ProdutosProvider();
  private categoriasProdutosProvider = new CategoriasProdutosProvider();

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
      produto: this.props.route.params?.produto ?? new Produto({}),
      categoriasProdutos: [],
      preco: "",
      camposValidos: true,
      erroTecnico: false,
      loadingSalvar: false,
      loadingSecundario: false
    }
  }

  /** Função chamada assim que a página é criada pela primeira vez */
  async componentDidMount() {
    //É adicionado um listener que é chamado sempre que a página é visitada
    this.props.navigation.addListener('focus', () => {
      this.carregarDados();
    });
  }

  /**
   * Carrega alguns dados do banco de dados, como lista de categorias de produtos e o produto com o atributo imagem
   */
  private async carregarDados(){
    this.setState({loadingSecundario: true});

    this.setState({preco: this.state.produto.preco ? this.state.produto.preco.toString() : ''});

    this.setState({categoriasProdutos: await this.categoriasProdutosProvider.buscarTodos()})

    //Se for uma edição, busca o produto para poder carregar a imagem
    if(this.state.produto.id_produto != undefined){
      let imagemProduto = (await this.produtosProvider.buscar(this.state.produto.id_produto)).imagem;
      this.setState({produto: {...this.state.produto, imagem: imagemProduto}});
    }

    this.setState({loadingSecundario: false});
  }

  /** Função responsável por pegar foto do Celular */
  async abrirGaleria(){
    let permissao = await Permissions.askAsync(Permissions.CAMERA_ROLL);    //await: Espera receber a resposta da função assincrona para poder passar para a próxima linha
    if(permissao.status == 'granted'){  //Se tiver permissão para usar recursos da câmera
        let foto:any = await ImagePicker.launchImageLibraryAsync({  //Busca foto da galeria
            allowsEditing: true,
            aspect: [4,3],
            base64: true,   //retornará a imagem como string
            mediaTypes: ImagePicker.MediaTypeOptions.Images,    //tipo de midia que será trazida
            quality: 0.3    //qualidade da imagem - vai de o a 1
        });

        if(!foto.cancelled){    //Se não tiver sido cancelada
            let produto =  this.state.produto;

            produto.imagem = 'data:image/jpeg;base64,'+foto.base64;

            this.setState({produto});
        }
    }
  }

  /**
   * Função que salva o produto (Insere se não existir o id_produto e edita se o id_produto já existir).
   */
  async salvar(){
    //https://stackoverflow.com/questions/43370176/using-async-setstate
    await this.setState({loadingSalvar: true});
    await this.setState({camposValidos: true});
    await this.setState({erroTecnico: false});

      //Validação de campos obrigatórios
      if(this.state.produto.nome == '' || this.state.preco == undefined || this.state.produto.categoria_id == 0){
        await this.setState({camposValidos: false});
      }

      //Salva no banco de dados se tiver tudo OK
      if(this.state.camposValidos == true){
        this.setState({produto: {...this.state.produto, preco: parseFloat(this.state.preco)}});

        if(this.state.produto.id_produto == undefined || this.state.produto.id_produto == 0){  //É um novo cadastro
          if(await this.produtosProvider.cadastrar(this.state.produto) == true){  //Tenta inserir no banco - Se conseguir, entra nessa condição.
            this.props.navigation.navigate('ListaProdutosScreen');
          }else{  //Se não tiver conseguido
            await this.setState({erroTecnico: true});
          }
        }else{  //É uma edição
          if(await this.produtosProvider.editar(this.state.produto) == true){
            this.props.navigation.navigate('ListaProdutosScreen');
          }else{
            await this.setState({erroTecnico: true});
          }
        }

        this.setState(this.getInitialState());  //Reseta o state
      }

      await this.setState({loadingSalvar: false});
  }

  public render() {
    return (
      <View style={{flex: 1, backgroundColor: '#212121'}}>
        <Toolbar titulo="Cadastro de produto" navigation={this.props.navigation} back={true} marginBottom={0} loading={this.state.loadingSelect} />
        <LoadingAnimation statusLoading={this.state.loadingSalvar} />

        <ScrollView>
            {/* Imagem da galeria */}
            <View style={styles.containerImagem}>
                <TouchableOpacity onPress={() => this.abrirGaleria()}>
                  <Image source={(this.state.produto.imagem ? {uri: this.state.produto.imagem} : require('./../../assets/imgs/sem-foto.jpg'))} style={styles.imagem} borderRadius={10} />                    
                </TouchableOpacity>
            </View>
            {/* Formulário */}
            <Input label="Nome" placeholder="Digite o nome" value={this.state.produto.nome} onChangeText={(nome) => {this.setState({produto: {...this.state.produto, nome}})}} containerStyle={styles.inputContainers} inputStyle={styles.inputStyles}></Input>
            <Input label="Descrição" placeholder="Digite a descrição" containerStyle={styles.inputContainers} inputStyle={styles.inputStyles} value={this.state.produto.descricao} onChangeText={(descricao) => {this.setState({produto: {...this.state.produto, descricao}})}}></Input>
            <Input label="Calorias" placeholder="Digite as calorias" containerStyle={styles.inputContainers} inputStyle={styles.inputStyles} keyboardType='number-pad' value={this.state.produto.calorias.toString()} onChangeText={(calorias) => {this.setState({produto: {...this.state.produto, calorias: parseInt(calorias) | 0}})}}></Input>
            <Input label="Preço" placeholder="Digite o preço" containerStyle={styles.inputContainers} inputStyle={styles.inputStyles} autoCapitalize='words' keyboardType='decimal-pad' value={this.state.preco} onChangeText={(preco) => {this.setState({preco: preco})}}></Input>
            <InputContainer label={"Categoria"} containerStyle={{...styles.inputContainers}}>
                <Picker selectedValue={this.state.produto.categoria_id} 
                        onValueChange = {(id_categoria_produto, itemPosition) => this.setState({produto: {...this.state.produto, categoria_id: id_categoria_produto}})}
                        style={{color: '#c6c6c4'}}>
                    <Picker.Item label="Selecione uma categoria" value={0} color="black" />
                    {this.state.categoriasProdutos.map((item, index) => {
                            return (<Picker.Item label={item.nome} value={item.id_categoria_produto} key={index} color="black" />) 
                    })}
                </Picker>
            </InputContainer>

            {(this.state.camposValidos == false || this.state.erroTecnico == true) && <View style={estilosPadroes.retanguloErro}>
              {this.state.camposValidos == false && <Text style={estilosPadroes.textoErro}>Por favor, preencha todos os campos.</Text>}
              {this.state.erroTecnico == true && <Text style={estilosPadroes.textoErro}>Houve um erro interno ao tentar salvar seu cadastro.</Text>}
            </View>}

            <Button title="Salvar" buttonStyle={{borderRadius: 20, backgroundColor: '#981e13', marginLeft: 50, marginRight: 50, marginTop: 20, marginBottom: 50}} onPress={() => this.salvar()}/>
        </ScrollView>
      </View>
    );
  }
}

const styles = StyleSheet.create({
    inputContainers: {
        marginBottom: 15
    },
    inputStyles: {
        color: '#c6c6c4'
    },
    containerImagem:{
      marginTop: 20,
      marginBottom: 20,
      marginLeft: 10,
      marginRight: 10,
      backgroundColor: 'rgba(255,255,255,0.6)',
      alignItems: 'center',
      borderTopWidth: 0.5,
      borderBottomWidth: 0.5,
      borderColor: '#839099',
    },
    imagem:{
        height: 150,
        width: 150,
        borderWidth: 3,
        borderRadius: 10,
        borderColor: '#981e13',
        //Se a imagem tiver a largura diferente da altura, é interessante usar o resizeMode: "contain" ou esticar com resizeMode: "stretch", para que a imagem não corte - https://medium.com/@mehrankhandev/understanding-resizemode-in-react-native-dd0e455ce63
        //resizeMode: "contain" 
    }
});
