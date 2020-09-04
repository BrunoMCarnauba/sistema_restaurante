import * as React from 'react';
import { View, StyleSheet, Text, ImageBackground, Image, KeyboardAvoidingView, Platform, ToastAndroid, Alert } from 'react-native';
import { Input, Button } from 'react-native-elements'
import { FuncionariosProvider } from '../providers/funcionarios';
import { LoadingAnimation } from '../components/loading';

export interface AppProps {
  navigation: any
}

export interface AppState {
  email: string,
  senha: string,
  emailValido: boolean,
  senhaValida: boolean,
  loginValido: boolean,
  loading: boolean
}

export default class LoginScreen extends React.Component<AppProps, AppState> {

  private funcionarioProvider = new FuncionariosProvider();

  constructor(props: AppProps) {
    super(props);
    this.state = {
      email: '',
      senha: '',
      emailValido: true,
      senhaValida: true,
      loginValido: true,
      loading: false
    };
  }

  public async autenticar(){
    console.log("Email = "+this.state.email+" Senha = "+this.state.senha);
    this.setState({emailValido: true});
    this.setState({senhaValida: true});
    this.setState({loading: true});

    //Validação
    if(this.state.email == "" || this.state.email == null || this.state.email.indexOf(".") == -1 || this.state.email.indexOf("@") == -1){
      this.setState({emailValido: false});
    }

    if(this.state.senha == "" || this.state.senha == null || this.state.senha.length < 6){
      this.setState({senhaValida: false});
    }

    //Autenticação
    if(this.state.emailValido == true && this.state.senhaValida == true){
      let respostaLogin = await this.funcionarioProvider.autenticar(this.state.email, this.state.senha);
      if(respostaLogin != null){
        this.props.navigation.reset({index: 0, routes: [{name: "Inicio"}]});  //Redefine a pilha de navegação, para que no menu ao apertar back não retorne para a página de login, e redireciona para a página do menu.
      }else{
        this.setState({loginValido: false});
        // if(Platform.OS == 'android'){
        //   ToastAndroid.show('Email ou senha incorreto.', 3000);
        // }else{
        //   Alert.alert('Erro', 'Email ou senha incorreto.');
        // }
      }
    }

    this.setState({loading: false});
  }

  public render() {
    return (
        <ImageBackground source={require('./../../assets/imgs/background-login.png')} style={styles.background}>
          <LoadingAnimation statusLoading={this.state.loading} />

          <View style={styles.tudo}>
            <KeyboardAvoidingView behavior={"position"} >
              <Image source={require('./../../assets/imgs/restaurant-logotipo2.png')} style={{width: '80%', height: 230, marginLeft: '10%', marginBottom: 5}}/>
              {/* <Text style={{color: 'white', fontSize: 35, textAlign: 'center', marginBottom: 7}}>Restaurante APP</Text> */}
                <View style={{alignItems: "stretch"}}>
                    <Input leftIcon={{name: 'person', color: 'white'}} leftIconContainerStyle={{marginRight: 5}} placeholder="Digite seu e-mail" inputStyle={{color: 'white'}} inputContainerStyle={styles.containerInput} keyboardType="email-address" onChangeText={(email) => { this.setState({email: email}) } } autoCapitalize = 'none' />
                    <Input leftIcon={{name: 'lock', color:'white'}} leftIconContainerStyle={{marginRight: 5}} placeholder="Digite sua senha" secureTextEntry={true} inputStyle={{color: 'white'}} inputContainerStyle={styles.containerInput} onChangeText={(senha) => { this.setState({senha: senha})}} autoCapitalize = 'none' />
                </View>  

                {(this.state.emailValido == false || this.state.senhaValida == false || this.state.loginValido == false) && <View style={styles.retanguloErro}>
                    {this.state.emailValido == false && <Text style={styles.textoErro}>Digite um email válido!</Text>}
                    {this.state.senhaValida == false && <Text style={styles.textoErro}>Digite uma senha com no mínimo 6 caracteres!</Text>}
                    {this.state.loginValido == false && <Text style={styles.textoErro}>Login inválido!</Text>}
                  </View>}

                <View style={{marginLeft: 40, marginRight: 40}}>
                  <Button title="Entrar" buttonStyle={{borderRadius: 50, backgroundColor: '#981e13', marginTop: 10}} onPress={() => this.autenticar()}/>
                </View>
              </KeyboardAvoidingView>
          </View>
        </ImageBackground>
    );
  }
}

const styles = StyleSheet.create({
    background: {
        width: '100%',
        height: '100%'
    },
    tudo:{
      flex: 1,
      flexDirection: "column",
      // justifyContent: "center",
      alignItems: "stretch",
      paddingTop: '15%'
    },
    containerInput: {
      backgroundColor: 'rgba(0, 0, 0, 0.5)',
      borderRadius: 30,
      padding: 5,
      marginBottom: 10
    },
    retanguloErro: {
      marginLeft: 20,
      marginRight: 20,
      borderRadius: 10,
      backgroundColor: 'rgb(190, 0, 0)',
      padding: 8,
      alignItems: 'center'
    },
    textoErro: {
      color: 'white',
      fontSize: 14,
      marginBottom: 5
    }
});
