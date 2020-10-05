API para prover e criar dados do Gerenciar de Postagens

API Hospedada no Heroku - http://gerenciamentopostapi.herokuapp.com

## Tecnologias

-   Pipelines de CI com GitHub Actions
-   PHPUnit para testes unitários
-   Php-cs-fixer para corrigir os padrões de codificação do PHP
-   Autenticação com JWT

### Ambiente

-   Docker

### API

-   REST
-   PHP 7.4 (FPM)
-   Laravel 7

# Instalação local 🚀🚀

**Você precisa instalar o Docker e o Docker-composer primeiro e, em seguida, para clonar o projeto via HTTPS, execute este comando:**

Clonando o projeto

```
git clone https://github.com/moisesrodriguesdev/gerenciamento-postagens.git
```

Entrar o diretório

```
cd gerenciamento-postagens
```

Em seguida executar o comando

```
docker-compose up
```

Ao executar o comando acima, será criado 3 containers

-   gerenciamento-postagens_php-fpm_1
-   gerenciamento-postagens_db_1
-   gerenciamento-postagens_webserver_1

Acessar o container 'gerenciamento-postagens_php-fpm_1'

```
docker exec -it gerenciamento-postagens_php-fpm_1 bash
```

Dentro do container acessar o diretório o /application

```
cd /application
```

Instalar dependência do Laravel

```
composer install
```

Configurar os parametros no arquivo .env (banco, token) https://laravel.com/docs/7.x#configuration

```
cp .env.example .env
```

Gerar Application Keys

```
php artisan key:generate
```

Fora do container execute os comandos abaixo para permissão as views

```
sudo chgrp -R www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache
```

# Execução 🚀🚀

**Você precisa criar duas tabelas na instancia do container Postgress, post_api e pgsql_test, em seguida execute este comando:**

Rode as migrations
```
php artisan migrate:fresh
```
Servidor de desenvolvimento
```
http://localhost:8000
```
