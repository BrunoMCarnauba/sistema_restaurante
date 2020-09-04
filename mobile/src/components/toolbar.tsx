import * as React from 'react';
import { View, Text, ActivityIndicator } from 'react-native';
import { Header, Icon } from 'react-native-elements';
import { TouchableOpacity } from 'react-native-gesture-handler';

export interface AppProps {
    titulo: string,
    navigation?: any,
    menu?: boolean,
    back?: boolean,
    backTo ?: string,
    loading ?: boolean,
    marginBottom ?: number
}

export function Toolbar (props: AppProps) {
    let leftComponent = null;
    let rightComponent = null;

    //Left component
    if(props.menu == true){
        leftComponent = <TouchableOpacity onPress={() => props.navigation.openDrawer()}>
                          <Icon name="menu" color="white" />
                        </TouchableOpacity>
    }
    if(props.back == true){
        leftComponent = <TouchableOpacity onPress={() => props.navigation.goBack()}>
                          <Icon name="arrow-back" color="white" />
                        </TouchableOpacity>
    }else if (props.backTo != undefined){
        leftComponent = <TouchableOpacity onPress={() => props.navigation.navigate(props.backTo)}>
                            <Icon name="arrow-back" color="white" />
                        </TouchableOpacity>
    }
    
    //Right Component
    if(props.loading != undefined){
        rightComponent = <ActivityIndicator animating={props.loading} size={28} color={'white'} />
    }

    //Estilo
    let estiloContainer = {backgroundColor: '#981e13', marginBottom: 20};
    if(props.marginBottom != undefined){
        estiloContainer.marginBottom = props.marginBottom;
    }

    return (
        <Header leftComponent={leftComponent} centerComponent={{text: props.titulo, style: {color: 'white', fontWeight: 'bold', fontSize: 18}}} rightComponent={rightComponent} 
            containerStyle={estiloContainer} statusBarProps={{ translucent: true }} />
    );
}
