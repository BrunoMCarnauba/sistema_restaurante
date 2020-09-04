import * as React from 'react';
import { View, Text, StyleSheet } from 'react-native';

//Novos tipos
type containerStyle = { borderBottomWidth?: number, borderBottomColor?: string, marginLeft?: number, marginRight?: number, marginBottom?: number };
type labelStyle = { color?: string, fontSize?: number, paddingBottom?: number };
type inputContainerStyle = { backgroundColor?: string, paddingTop?: number, paddingBottom?: number };

/** Propriedades do Container */
export interface AppProps {
    children: any;
    label: string;
    descricao?: string,
    containerStyle?: containerStyle,
    labelStyle?: labelStyle,
    inputContainerStyle?: inputContainerStyle
}

/**
 * Design do Input Container.
 * É possível personalizá-lo pelas propriedades containerStyle, labelStyle e inputContainerStyle.
 * @param props 
 */
export function InputContainer (props: AppProps) {
    return (
        // O primeiro style são os estilos padrões. O segundo style é o preenchido pelo usuário, se esse style tiver tiver preenchid o mesmo atributo do primeiro, ele sobrescreve esse atributo.
        <View style={{...styles.container, ...props.containerStyle}}>
            <Text style={{...styles.inputLabel, ...props.labelStyle}}>{props.label}  <Text style={{...styles.inputDescription, fontSize: (styles.inputLabel.fontSize-2)}}>{props.descricao}</Text></Text>
            <View style={{...styles.inputContainer, ...props.inputContainerStyle}}>
                {props.children}
            </View>
        </View>
    );
}

//Css a  ser aplicado 
const styles = StyleSheet.create({
    container: {
        borderBottomWidth: 1,
        borderBottomColor: '#839099',
        marginLeft:10,
        marginRight: 10,
        marginBottom: 5
    },
    inputLabel: {
        color: '#839099',
        fontSize: 16,
        fontWeight: 'bold',
        paddingBottom: 0
    },
    inputDescription: {
        color: '#839099',
        fontWeight: 'normal'
    },
    inputContainer: {
        backgroundColor: 'transparent', 
        paddingTop: 0, 
        paddingBottom: 0
    }
});