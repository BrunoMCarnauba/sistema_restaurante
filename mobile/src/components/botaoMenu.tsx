import * as React from 'react';
import { View, StyleSheet, Text, TouchableOpacity } from 'react-native';
import { Icon } from 'react-native-elements';

type style = { backgroundColor?:string, marginLeft?: number, marginRight?: number, marginTop?: number, marginBottom?: number,
         borderWidth?: number, borderColor?: string, borderRadius?: number, width?: number, height?: number }

export interface AppProps {
    title: string,
    iconName: string,
    iconType: string,
    onPress?: any,
    style?: style
}

export interface AppState {
}

/**
 * Componente que renderiza botão do menu
 * @param props 
 */
export default class BotaoQuadrado extends React.Component<AppProps, AppState> {

  //Valores padrões do props - caso não tenha sido definido ao chamar o componente
  static defaultProps = {
    marginRight: 0
  }

  constructor(props: AppProps) {
    super(props);
    this.state = {
    };
  }

  public render() {
    return (
        <View>
            <TouchableOpacity style={{...styles.buttonContainer, ...this.props.style}} onPress={this.props.onPress}>
              <View>
                <Icon name={this.props.iconName} color="white" type={this.props.iconType} />
                <Text style={styles.buttonText}>{this.props.title}</Text>
              </View>
            </TouchableOpacity>
        </View>
    );
  }
  
}

const styles = StyleSheet.create({
    buttonContainer: {
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: '#981e13',
        height: 100,
        width: 140,
        marginBottom: 30,
        borderRadius: 4,
    },
    buttonText: {
        color: 'white'
    }
  });