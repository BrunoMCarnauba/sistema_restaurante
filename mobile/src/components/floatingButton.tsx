import * as React from 'react';
import { View, StyleSheet } from 'react-native';
import { Icon } from 'react-native-elements';
import { TouchableOpacity } from 'react-native-gesture-handler';

/** Propriedades do FabButton */
export interface AppProps {
    top?:boolean;
    left?:boolean;
    icon?: string;
    onPress():void;
}

/**
 * Icone flutuante 
 * @param props 
 */
export function Fab (props: AppProps) {
    //Define qual css será aplicado com base na posiçao
    let extras = [];
    extras.push(props.top ? styles.top : styles.bottom);    //Se top == true então guarde o objeto styles.top, se não guarde o styles.bottom
    extras.push(props.left ? styles.left : styles.right);

    return (
      <View style={[styles.default, ...extras]}>
          <TouchableOpacity onPress={props.onPress}>
            <Icon raised reverse name={props.icon} color='#981e13' />
         </TouchableOpacity>
      </View>
    );
}
//Define as propriedades padrões de um componente passado como função
Fab.defaultProps = { icon: 'add' }

//Css a  ser aplicado 
const styles = StyleSheet.create({
    default: { position: 'absolute' },
    bottom: { bottom: 10 },
    top: { top: 10},
    left: { left: 10},
    right: { right: 10}
});