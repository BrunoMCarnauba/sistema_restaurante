import * as React from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createStackNavigator } from '@react-navigation/stack';
import DrawerNavigator from './drawer-menu';
import { FuncionariosProvider } from '../providers/funcionarios';
import LoginScreen from '../screen/login';
import PedidoScreen from '../screen/pedidoScreen';
import ListaProdutosScreen from '../screen/listaProdutos';
import DetalheProdutoScreen from '../screen/detalheProduto';

const Stack = createStackNavigator();

function AppStack(){
    return(
        <NavigationContainer>
            <Stack.Navigator initialRouteName={"Login"} screenOptions={{headerShown: false}}>
                <Stack.Screen name="LoginScreen" component={LoginScreen} />

                <Stack.Screen name="Inicio" component={DrawerNavigator} />
                <Stack.Screen name="PedidoScreen" component={PedidoScreen} />
                <Stack.Screen name="ListaProdutosScreen" component={ListaProdutosScreen} />
                <Stack.Screen name="DetalheProdutoScreen" component={DetalheProdutoScreen} />
            </Stack.Navigator>
        </NavigationContainer>
    );
}

export default AppStack;