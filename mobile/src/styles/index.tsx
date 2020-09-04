//https://blog.rocketseat.com.br/como-organizar-estilos-no-react-native/
import { StyleSheet } from 'react-native';

const styles = StyleSheet.create({
    retanguloErro: {
        marginTop: 20,
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

export default styles;