# Aplicativo do sistema de restaurante

O aplicativo foi desenvolvido para a disciplina de programação para dispositivos móveis do curso de sistemas de informação. A ideia do sistema é permitir ao garçom registrar os pedidos das mesas por meio do aplicativo, de forma que a cozinha tenha acesso através do sistema web a esses pedidos realizados. Você pode conferir o funcionamento do sistema assistindo a esse [video de demonstração](https://youtu.be/6SD4YR1-1Gs).

## Descrição técnica

Aplicativo móvel desenvolvido com React Native usando o [expo 38.0](https://docs.expo.io/) e o typescript <br/>

### Preparando o ambiente

* Aplicativo móvel <br/>

Instale o [Node.js](https://nodejs.org/en/) <br />

Instale o expo rodando o seguinte comando no prompt de comandos: 
```
npm install –g  expo-cli
```

Instale o Typescript rodando o comando:
```
npm install –g  typescript
```

Instale as dependências do projeto rodando o seguinte comando no diretório raíz do aplicativo:
```
npm install
```

Para ter acesso a API rodando na rede local, coloque o endereço IPv4 do seu computador em baseURL, no arquivo providers/api.ts. Exemplo: 'http://192.168.1.10:3333/api'. No windows você pode encontrar o endereço IPv4 digitando "ipconfig" no prompt de comando.

### Iniciando o aplicativo

Para iniciar o aplicativo em um celular que esteja conectado ao computador, ou pelo emulador, rode o seguinte comando no diretório raíz do aplicativo:
```
expo start --localhost
```
Irá abrir uma página no seu navegador, se o aplicativo não iniciar automaticamente clique no botão "Run on Android device/emulator".