import Axios from 'axios';
import { AsyncStorage } from 'react-native';

export abstract class APIProviders{

    protected api = Axios.create({
        baseURL: 'http://192.168.1.4:3333/api'   //http://enderecoIPV4:porta/api exemplo: http://192.168.1.10:3333/api
    });

    /* Adiciona o Token ao Headers  */
    protected async getToken(){
        this.api.defaults.headers.common['Authorization'] = await AsyncStorage.getItem('token');
    }
}