API para prover e criar dados do Gerenciar de Postagens

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

# Servidor de desenvolvimento 🚀🚀

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

Será necessário da permissão para as views acessar os storage

```
sudo chgrp -R www-data storage bootstrap/cache
sudo chmod -R ug+rwx storage bootstrap/cache
```
