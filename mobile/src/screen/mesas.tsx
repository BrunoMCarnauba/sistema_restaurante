import * as React from 'react';
import { View, StyleSheet, FlatList, ActivityIndicator } from 'react-native';
import { Toolbar } from '../components/toolbar';
import BotaoMesa from '../components/botaoMesa'
import Mesa from '../models/mesa';
import { MesasProvider } from '../providers/mesas';

export interface AppProps {
  navigation?: any
}

export interface AppState {
  mesas: Mesa[],
  loading: boolean
}

export default class MesasScreen extends React.Component<AppProps, AppState> {

  private mesasProvider = new MesasProvider();

  constructor(props: AppProps) {
    super(props);
    this.state = {
      mesas: [],
      loading: false
    };
  }

  /** Função chamada assim que a página é criada pela primeira vez */
  async componentDidMount() {
    //É adicionado um listener que é chamado sempre que a página é visitada
    this.props.navigation.addListener('focus', () => {
      this.setState({loading: true});
      this.mesasProvider.buscarTodos().then(mesas => {
        this.setState({mesas});
        this.setState({loading: false});
      });
    });
  }

  public render() {
    return (
      <View style={{flex: 1, backgroundColor: '#212121'}}>
        <Toolbar titulo="Mesas" navigation={this.props.navigation} backTo='MenuScreen' />

        {this.state.loading == true &&
          <ActivityIndicator animating={this.state.loading} size={'large'} color={'#981e13'} />
        }
        
        <FlatList contentContainerStyle={styles.listaBotoes} data={this.state.mesas} keyExtractor={(item) => item.id_mesa.toString()} numColumns={2}
                  renderItem={({item, index}) => {
                    let marginRight = 0;
                    if(index%2 == 0){ //Adiciona marginRight apenas aos botões de index par
                      marginRight = 20;
                    }

                    return (<BotaoMesa title={"Mesa 0"+item.id_mesa} status={item.status} style={{marginRight: marginRight}}
                                    onPress={() => this.props.navigation.navigate('PedidoScreen', {mesa: item})} />);
                  }} />
      </View>
    );
  }
}

const styles = StyleSheet.create({
  listaBotoes: {
    alignItems: 'center',
    paddingTop: 20,
  }
});
