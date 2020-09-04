# Sistema de restaurante

Sistema de restaurante desenvolvido para a disciplina de programação para dispositivos móveis do curso de sistemas de informação. A ideia do sistema é permitir ao garçom registrar os pedidos das mesas por meio do aplicativo, de forma que a cozinha tenha acesso através do sistema web a esses pedidos realizados. Você pode conferir o funcionamento desse sistema assistindo a esse [video de demonstração](https://youtu.be/6SD4YR1-1Gs).

## Descrição técnica

Sistema web desenvolvido em PHP com [Laravel 6.0.3](https://laravel.com/docs/6.x/) <br/>
Aplicativo móvel desenvolvido com React Native usando o [expo 38.0](https://docs.expo.io/) e o typescript <br/>
O sistema de gerenciamento de banco de dados utilizado foi o phpMyAdmin

### Preparando o ambiente

* Sistema web / API <br/>

Instale o [Xampp](https://www.apachefriends.org/pt_br/index.html), isso instalará o PHP e também o phpMyAdmin; <br/>
Instale o gerenciador de dependências [Composer](https://getcomposer.org/download/); <br/>
Instale as dependências do projeto rodando o seguinte comando no diretório raíz do sistema web:
```
composer install
```
Duplique o arquivo .env.example, renomeie para .env e rode o comando:
```
php artisan key:generate
```
Inicie o Apache e o MySQL no painel de controle do Xampp, e em MySQL aperte em Admin para abrir o phpMyAdmin. Nele, crie um novo banco de dados com charset utf8_general_ci <br/>
Após a criação, no arquivo .env edite o nome do banco de dados na linha DB_DATABASE e coloque o usuário e senha do banco de dados em DB_USERNAME e DB_PASSWORD respectivamente. No phpMyAdmin o valor padrão dessas crendencias é root para username e vazio para password.<br/>
Para criar as tabelas de forma automática, rode o comando:
```
php artisan migrate
```
Para preencher o banco de dados com os valores iniciais, rode o comando:
```
php artisan db:seed
```
Por fim, execute o comando abaixo para que a pasta onde são salvas as imagens dos produtos fique disponível no public:
```
php artisan storage:link
```

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


### Iniciando o sistema web

Para iniciar o servidor na rede local, de forma que você possa acessá-lo pelo celular, inicie o Apache e o MySQL no painel de controle do XAMPP e execute o seguinte comando no diretório raíz do sistema web: <br/>
```
php artisan serve --host 0.0.0.0 --port 3333
```
Com isso você pode entrar no sistema web acessando o endereço IPv4 da sua máquina na porta 3333, por exemplo: http://192.168.1.10:3333.

### Iniciando o aplicativo

Para iniciar o aplicativo em um celular que esteja conectado ao computador, ou pelo emulador, rode o seguinte comando no diretório raíz do aplicativo:
```
expo start --localhost
```
Irá abrir uma página no seu navegador, se o aplicativo não iniciar automaticamente clique no botão "Run on Android device/emulator".

### Extra

As imagens salvas dos produtos ficam no diretório storage/app/public/imagens/produtos do sistema web.

### O que falta implementar?

- Implementar filtros das listas do aplicativo;
- Adicionar permissões de acesso para os cargos.