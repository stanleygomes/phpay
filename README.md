[![CodeFactor](https://www.codefactor.io/repository/github/stanleygomes/php-wan/badge)](https://www.codefactor.io/repository/github/stanleygomes/php-wan)

# PHP-WAN

Come to the PHP side 🚀 🌑

The main goal of **php-wan** is to set patterns to be easily implemented on Nodejs projects. We want to make easy to quick start a PHP ambient with the basic resources every project could have. Check out the patterns we defined this document bellow.

<p  align="center" style="padding:15px 0;">
	<img src="https://i.imgur.com/mQvQ1tL.png" width="400px" />
  <br />
  Icon by <a href="https://dribbble.com/creativeflip" target="_blank">Filipe Carvalho</a>
</p>

*******
Table of contents 
 1. [How to get Started](#startup)
 2. [Gitflow recommendations](#gitflow)
 3. [Patterns and libs](#patterns)
 4. [Samples and tutorials of use](#tutorials)
 5. [Project structure](#projetcstructure)
*******

<div id='startup'/>

## Startup

Step by step to get this up and running

### Clone repo and go to project folder

```
git clone https://github.com/stanleygomes/php-wan.git && cd php-wan
```

### Install dependencies

```bash
composer install
```

### Great vscode extension

We recomend use of [VS Code](https://code.visualstudio.com) and [PHP Intelephense](https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client) extension.

### Start server

Copy enviroment variables template

```bash
cp .env.template .env
```

Via docker-compose (start database, run migrations and start server)

```bash
docker-compose up
```

To test it on the browser, simply go to: `http://localhost:8000/welcome`

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
- Linter: eslint [standard](https://standardjs.com) pattern
- Database ORM: Use [Eloquent](https://laravel.com/docs/7.x/eloquent)
- Migrations: Run on a container described in docker-compose file: [boxfuse/flyway](https://hub.docker.com/r/boxfuse/flyway/dockerfile) image
- Template transpiler: [Mustache](https://mustache.github.io) templates
- Localization: take a look [here](https://laravel.com/docs/7.x/localization)
- Date and time: [carbon](https://carbon.nesbot.com/)
- Test: [PHP Unity](https://phpunit.de/index.html)
- Logs: [Laravel logging](https://laravel.com/docs/7.x/logging)
- Authentication: [JWT](https://www.npmjs.com/package/jwt)
- SMTP email: Send emails using [Laravel mail](https://laravel.com/docs/7.x/mail) and blade templates with mustache
- Docker compose and dockerfile attached running migrations e starting database and nodejs

<div id='projetcstructure'/>

## Project structure

Basic folder structure

- **TODO/TODO**: App config (some of these are inherited from .env file), constants, configuration and i18n
- **TODO**: Endpoints and business logic
- **TODO**: Images, styles, fonts and other files that can be served
- **TODO**: Middlewares for routes
- **TODO**: Routes, :]
- **TODO**: mustache interpreted files
- **TODO**: Mocha and chai unity tests
- **TODO**: Utilities and modules superior layer implementations
