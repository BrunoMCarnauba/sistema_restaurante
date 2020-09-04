import * as React from 'react';
import { View, StyleSheet, Text, Linking } from 'react-native';
import { Icon } from 'react-native-elements';
import { Toolbar }  from '../components/toolbar';
import BotaoQuadrado from '../components/botaoMenu';
import { AdMobBanner, setTestDeviceIDAsync } from 'expo-ads-admob';
import { FuncionariosProvider } from '../providers/funcionarios';


export interface AppProps {
  navigation: any
}

export interface AppState {
}

export default class MenuScreen extends React.Component<AppProps, AppState> {

  private funcionariosProvider = new FuncionariosProvider();

  constructor(props: AppProps) {
    super(props);
    this.state = {
    };
  }

  async componentDidMount(){
    this.iniciarAdMob();
  }

  /**
   * Indica que está rodando em ambiente de teste. Para evitar de ser penalizado pelo google por acessar várias vezes uma propaganda no ambiente de teste.
   */
  private async iniciarAdMob(){
    await setTestDeviceIDAsync('EMULATOR').catch(() => console.log("Erro ao tentar indicar ao AdMob que ele está em ambiente de teste"));
  }

  public logout(){
    this.funcionariosProvider.logout();
    this.props.navigation.navigate('LoginScreen');
  }
  
  public render() {
    return (
      <View style={{flex: 1, backgroundColor: '#212121'}}>
        <Toolbar titulo="Menu" navigation={this.props.navigation} menu={true} />

        <View style={styles.listaBotoes}>
          <BotaoQuadrado title="Funcionários" iconName="user" iconType="font-awesome" onPress={() => this.props.navigation.navigate('ListaFuncionariosScreen')} style={{marginRight: 20}} />
          <BotaoQuadrado title="Produtos" iconName="food" iconType="material-community" onPress={() => this.props.navigation.navigate('ListaProdutosScreen')} />
          <BotaoQuadrado title="Mesas" iconName="table" iconType="font-awesome" onPress={() => this.props.navigation.navigate('MesasScreen')} style={{marginRight: 20}} />
          <BotaoQuadrado title="Sistema web" iconName="web" iconType="material-community" onPress={() => Linking.openURL('http://192.168.1.4:3333/')}  />
          <BotaoQuadrado title="Ajuda" iconName="md-help" iconType="ionicon" onPress={() => Linking.openURL('https://youtu.be/6SD4YR1-1Gs')} style={{marginRight: 20}} />
          <BotaoQuadrado title="Sair" iconName="logout" iconType="material-community" onPress={() => this.logout()} />
        </View>

        <View style={{alignItems: 'center'}}>
          {/* O adUnitID setado é de teste. Ele também está presente na configuração no app.json */}
          <AdMobBanner bannerSize="largeBanner" adUnitID="ca-app-pub-3940256099942544/6300978111" servePersonalizedAds={true} onDidFailToReceiveAdWithError={ (erro) => console.log("Erro ao carregar banner do AdMob: "+erro) }/>
        </View>

      </View>
    );
  }
}

const styles = StyleSheet.create({
  listaBotoes: {
    flex: 1,
    flexDirection: 'row',
    flexWrap: 'wrap',
    justifyContent: 'center',
    marginTop: 40,
  }
});