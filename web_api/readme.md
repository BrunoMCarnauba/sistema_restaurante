# Gerenciador web e api do sistema de restaurante

Gerenciador web e api do aplicativo de restaurante desenvolvido para a disciplina de programação para dispositivos móveis do curso de sistemas de informação. A ideia do sistema é permitir ao garçom registrar os pedidos das mesas por meio do aplicativo, de forma que a cozinha tenha acesso através do sistema web a esses pedidos realizados. Você pode conferir o funcionamento do sistema assistindo a esse [video de demonstração](https://youtu.be/6SD4YR1-1Gs).

## Descrição técnica

Sistema web desenvolvido em PHP com [Laravel 6.0.3](https://laravel.com/docs/6.x/) <br/>
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

### Iniciando o sistema web

Para iniciar o servidor na rede local, de forma que você possa acessá-lo pelo celular, inicie o Apache e o MySQL no painel de controle do XAMPP e execute o seguinte comando no diretório raíz do sistema web: <br/>
```
php artisan serve --host 0.0.0.0 --port 3333
```
Com isso você pode entrar no sistema web acessando o endereço IPv4 da sua máquina na porta 3333, por exemplo: http://192.168.1.10:3333.