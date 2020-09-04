import * as React from 'react';
import { View, StyleSheet, Text, TouchableOpacity } from 'react-native';
import { Icon, Badge } from 'react-native-elements';

type style = { backgroundColor?:string, marginLeft?: number, marginRight?: number, marginTop?: number, marginBottom?: number,
  borderWidth?: number, borderColor?: string, borderRadius?: number, width?: number, height?: number }

export interface AppProps {
    title: string,
    /** disponivel, espera, ocupada */
    status: string,
    onPress?: any,
    style?: style
}

export interface AppState {
}

/**
 * Componente que renderiza botão da tela de mesas
 * @param props 
 */
export default class BotaoMesa extends React.Component<AppProps, AppState> {
  constructor(props: AppProps) {
    super(props);
    this.state = {
    };
  }

  public render() {
    let statusMesa:any = "success";
    let statusValue = "Disponível";
    if(this.props.status == "Aguardando"){
      statusMesa = "warning";
      statusValue = "Em espera";
    }else if(this.props.status == "Ocupado"){
      statusMesa = "error";
      statusValue = "Ocupada";
    }

    return (
        <View>
            <TouchableOpacity style={{...styles.buttonContainer, ...this.props.style}} onPress={this.props.onPress}>
              <View style={{marginTop: 11}}>
                <Icon name='table' color="white" type='material-community' />
                <Text style={styles.buttonText}>{this.props.title}</Text>
                <Badge containerStyle={{marginTop: 4}} status={statusMesa} value={statusValue} />
              </View>
            </TouchableOpacity>
        </View>
    );
  }
  
}

const styles = StyleSheet.create({
    buttonContainer: {
        flex: 1,
        backgroundColor: '#981e13',
        alignItems: 'center',
        height: 100,
        width: 140,
        marginBottom: 30,
        borderRadius: 4
    },
    buttonText: {
        color: 'white'
    }
  });

