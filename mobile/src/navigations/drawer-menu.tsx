import * as React from 'react';
import { createDrawerNavigator, DrawerContentScrollView, DrawerItemList, DrawerItem } from '@react-navigation/drawer';
import { View } from 'react-native';
import { Icon } from 'react-native-elements';
import { FuncionariosProvider } from '../providers/funcionarios';
import MenuScreen from '../screen/menu';
import MesasScreen from '../screen/mesas';
import ListaProdutosScreen from '../screen/listaProdutos';
import CadastroProdutoScreen from '../screen/cadastroProduto';
import ListaFuncionariosScreen from '../screen/listaFuncionarios';
import CadastroFuncionarioScreen from '../screen/cadastroFuncionario';

const Drawer = createDrawerNavigator();

function DrawerNavigator(){
    return(
        <Drawer.Navigator initialRouteName={"MenuScreen"} drawerContent={props => <DrawerContent {...props} />} 
                drawerContentOptions={{activeTintColor: 'white', inactiveTintColor: 'white', activeBackgroundColor: '#981e13'}}>

            <Drawer.Screen name="MenuScreen"  component={MenuScreen} options={{title: 'Menu principal', drawerIcon: ({color}) => <Icon name="menu" color={color} />}} />

            <Drawer.Screen name="ListaFuncionariosScreen" component={ListaFuncionariosScreen} options={{title: 'Lista de usuários', drawerIcon: ({color}) => <Icon name="list" type="font-awesome" color={color}/>}} />
            <Drawer.Screen name="CadastroFuncionarioScreen" component={CadastroFuncionarioScreen} options={{title: 'Cadastro de usuário', drawerIcon: ({color}) => <Icon name="user" type="font-awesome" color={color} />}} />

            <Drawer.Screen name="ListaProdutosScreen" component={ListaProdutosScreen} options={{title: 'Lista de produtos', drawerIcon: ({color}) => <Icon name="restaurant-menu" type="material" color={color} />}} />
            <Drawer.Screen name="CadastroProdutoScreen" component={CadastroProdutoScreen} options={{title: 'Cadastro de produto', drawerIcon: ({color}) => <Icon name="food" type="material-community" color={color} />}} />

            <Drawer.Screen name="MesasScreen" component={MesasScreen} options={{title: 'Mesas', drawerIcon: ({color}) => <Icon name="table" type="font-awesome" color={color} />}} />

        </Drawer.Navigator>
    );
}

/**
 * Personalização do drawer menu
 * @param props 
 */
function DrawerContent(props){
    return(
        <View style={{flex: 1, backgroundColor: '#212121'}}>
            <DrawerContentScrollView {...props}>
                <DrawerItemList {...props}/>
            </DrawerContentScrollView>
            <DrawerItem
                    {...props}
                    icon={({color}) =><Icon name="logout" type="material-community" color={color} />}
                    label={"Sair"}
                    onPress={() => {
                        let funcionarioProvider = new FuncionariosProvider;
                        funcionarioProvider.logout();
                        props.navigation.navigate('LoginScreen');
                    }}
                />
        </View>
    );
}


export default DrawerNavigator;