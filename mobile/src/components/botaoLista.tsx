import * as React from 'react';
import { View, StyleSheet, Text } from 'react-native';
import { TouchableOpacity } from 'react-native-gesture-handler';
import Swipeout from 'react-native-swipeout';
import ItemPedido from '../models/itemPedido';

export interface AppProps {
    nome: string;
    quantidade ?: number;
    preco?: number;
    onExcluir ?: any;  //Manda executar uma ação ao tentar excluir tarefa
    onEditar ?: any;
    onPress ?: any;
}

/**
 * Componente que renderiza os produtos - Se tiver vindo da página de pedido ou 
 * @param props 
 */
export function BotaoLista (props: AppProps) {
    // let fazendoPedido: boolean = true; //Se tiver vindo da tela
    // if(props.quantidade == undefined){
    //   fazendoPedido = false;
    // }

    return (
        <View>
          {(props.onPress == undefined) && 
            <Swipeout style={styles.containerExterno} right = {[{text:'Editar', backgroundColor:'green', color:'white', onPress:()=> props.onEditar()}, {text: 'Excluir', backgroundColor:'red', color:'white', onPress:() => props.onExcluir()}]}>
                <View style={styles.containerInterno}>
                  {(props.quantidade != undefined) && <Text style={styles.quantidade}>{props.quantidade}x</Text>}
                  <Text style={styles.nome}>{props.nome}</Text>
                  {(props.preco != undefined) && <Text style={styles.preco}>R${parseFloat(''+props.preco).toFixed(2)}</Text>}
                </View>
            </Swipeout>
          }

          {(props.onPress != undefined) &&
            <View style={styles.containerExterno}>
              <TouchableOpacity style={styles.containerInterno} onPress={() => props.onPress()}>
                {(props.quantidade != undefined) && <Text style={styles.quantidade}>{props.quantidade}x</Text>}
                <Text style={styles.nome}>{props.nome}</Text>
                {(props.preco != undefined) && <Text style={styles.preco}>R${parseFloat(''+props.preco).toFixed(2)}</Text>}
              </TouchableOpacity>
            </View>
          }

        </View>
    );
}

//Estilo usado no componente do item do pedido
//alignItems flex-end: https://stackoverflow.com/questions/41782937/align-items-flex-end-react-native
const styles = StyleSheet.create({
    containerExterno: {
        backgroundColor: 'rgba(255,255,255,0.8)',
        marginBottom: 5,
        borderRadius: 5
    },
    containerInterno:{
      flexDirection: 'row',
      paddingTop: 20,
      paddingBottom: 20,
      paddingLeft: 10,
      paddingRight: 10
    },
    quantidade:{
        fontSize: 20,
        color: '#136e27',
        fontWeight: 'bold',
        textAlignVertical: 'top',
        marginRight: 10
    },
    nome: {
        flex: 1,
        fontSize: 18,
        textAlignVertical: 'top'
    },
    preco: {
      fontSize: 18,
      textAlignVertical: 'top',
      marginLeft: 60
    }
});
