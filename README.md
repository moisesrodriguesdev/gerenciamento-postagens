API para prover os dados do registroCOVID

## Tecnologias

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

-   gerenciamento-postagens-php-fpm
-   gerenciamento-postagens-mysql
-   gerenciamento-postagens-webserver

Acessar o container 'gerenciamento-postagens-php-fpm'

```
docker exec -it gerenciamento-postagens-php-fpm bash
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
