[![CodeFactor](https://www.codefactor.io/repository/github/stanleygomes/phpay/badge)](https://www.codefactor.io/repository/github/stanleygomes/phpay)

# PHPAY

An open PHP store platform. Focused in small businesses 💰 🛒

The main goal of **phpay** is to set patterns to be easily implemented on a web store. We want to make easy to quick start a PHP ambient with the basic resources every store need. Check out the patterns we defined this document bellow.

*******
Table of contents 
 1. [How to get Started](#startup)
 2. [Gitflow recommendations](#gitflow)
 3. [Patterns and libs](#patterns)
*******

<div id='startup'/>

## Startup

Step by step to get this up and running

### Clone repo and go to project folder

```
git clone https://github.com/stanleygomes/phpay.git && cd phpay
```

### Start via docker compose

First, copy enviroment variables for your application

```bash
cp .env.template .env
```

Then, start containers (this command will start database and run migrations)

```bash
docker-compose up
```

Verify if the containers are up and running

```bash
docker container ls
```

Now, access the webserver container

```
docker container exec -it phpay-webserver /bin/bash
```

At this point, your are connected to webserver container. Install composer dependencies

```bash
composer install
```

After this step is finished, you can exit the container:

```bash
exit
```

Since you are back to your terminal, you may need to change some folder permissions

```bash
sudo chmod -R 0775 bootstrap storage vendor
```

Finally, go to: `http://localhost:8000` to see your app online

### Great vscode extension

We recomend use of [VS Code](https://code.visualstudio.com) and [PHP Intelephense](https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client) extension.

### Services

- Mercado Pago API: https://github.com/mercadopago/dx-php
- Pagseguro API: https://github.com/pagseguro/pagseguro-php-sdk
- [TODO] PagarMe API: https://github.com/pagarme/pagarme-php
- [TODO] NFe PHP: https://github.com/nfephp-org/sped-nfe

<div id='gitflow'/>

## Git flow

To file a new a feature

- create a branch from `master` branch. Use the pattern: `feature/description`
- file a pull request on `master` branch
- since your PR is aproved, it will be merged to `master` branch
- in a moment in time we'll create a release, using the pattern: `release/vX.X.X`

<div id='patterns'/>

## Patterns

These are some of patterns definitions to help us to keep a default arquitecture.

- Package manager: [composer](https://getcomposer.org), sure
- PHP version: [v7.3.x](https://www.php.net/releases/7_3_0.php)
- PHP Framework: [Laravel 6.x](https://laravel.com/docs/6.x) framework
- Database ORM: Use [Eloquent](https://laravel.com/docs/7.x/eloquent)
- Migrations: Run on a container described in docker-compose file: [boxfuse/flyway](https://hub.docker.com/r/boxfuse/flyway/dockerfile) image
- Localization: take a look [here](https://laravel.com/docs/7.x/localization)
- Date and time: [carbon](https://carbon.nesbot.com/)
- Test: [PHP Unity](https://phpunit.de/index.html)
- Logs: [Laravel logging](https://laravel.com/docs/7.x/logging)
- SMTP email: Send emails using [Laravel mail](https://laravel.com/docs/7.x/mail) and blade templates with mustache
- Docker compose and dockerfile attached running migrations e starting database and nodejs
