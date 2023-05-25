# Portail de quiz et drill pour examens sur ordinateur

## Introduction
Ce projet est réalisé dans le cadre de mon travail de Bachelor dans la Haute École d'Ingénierie et de Gestion du Canton de Vaud (HEIG-VD). Il a pour but de refactor le code d'une plateforme de quiz interactive existante afin de la rendre plus maintenable et évolutive. Il doit également ajouter quelques fonctionnalités comme la possibilité de faire des exercices de type "drill" et de pouvoir faire des examens sur ordinateur.

Vous pouvez trouver le repository du projet original [ici](https://github.com/heig-vd-tin/heig-quiz).

## Installation
Technologies utilisées dans ce projet :
|   Technologie| version  |
|---|---|
|  MySQL | 8.0  |
|PHP|8.2.6|
|Composer|2.5.5|
|Laravel|10|
|Node|18.15.0|
|NPM|9.5.0|
|Vue.js|3|
|TailwindCss|3.3|
|WSL|2|
|Docker|20.10.22|
|Ubuntu|22.04.2 LTS|
|Keycloak|21.1|

### Docker
Pour installer ce projet, vous aurez besoin de Docker. Vous pouvez le télécharger [ici](https://www.docker.com/).

Si vous êtes sur un système d'exloitation Windows, vous aurez également besoin de [WSL 2](https://learn.microsoft.com/fr-fr/windows/wsl/install) et la version [22.04.2 de Ubuntu](https://apps.microsoft.com/store/detail/ubuntu-22042-lts/9PN20MSR04DW?hl=fr-ch&gl=ch&rtc=1). Vous pouvez le télécharger en suivant les liens.

### PHP
Une fois Docker installé, Installez la version de PHP qui vous convient en suivant ce lien : [guide d'installation de php](https://tecadmin.net/how-to-install-php-on-ubuntu-22-04/).

Activez les exentions php-curl, php-xml et de mysql grace à la commande suivante: 
```bash
sudo apt-get install php-curl php-xml php-mysql
```

### Composer
Installez composer en suivant ce lien : [guide d'installation de composer](https://getcomposer.org/download/).

Pour activer composer globalement il faut également faire la commande suivante:
```bash
sudo mv composer.phar /usr/local/bin/composer
```

### Node et NPM
Installez Node et NPM en suivant l'option 2 de ce guide : [guide d'installation de Node.js et NPM](https://www.digitalocean.com/community/tutorials/how-to-install-node-js-on-ubuntu-22-04#option-2-installing-node-js-with-apt-using-a-nodesource-ppa)


## Cloner le projet
Pour cloner le projet, il faut d'abord se rendre dans le dossier où vous voulez le cloner. Ensuite, cliquez sur le bouton vert en haut de cette page et sélectionnez l'option de votre choix (HTTPS, SSH ou GitHub CLI). Copiez le lien et entrez la commande suivante dans votre terminal :
```bash
# Exemple pour HTTPS
git clone https://github.com/Marinlestylo/h-quiz.git
```

## Lancer le backend
Avant de lancer le projet, il vous faut lancer la base de données. Pour cela, il vous suffit de vous rendre dans le dossier `api-backend` et de lancer la commande suivante :
```bash
docker-compose up
```

### Keycloak (optionnel)
Si vous n'avez pas de serveur keycloak, vous pouvez en lancer un grace à la commande suivante :
```bash
docker run -p 8080:8080 -e KEYCLOAK_ADMIN=admin -e KEYCLOAK_ADMIN_PASSWORD=admin quay.io/keycloak/keycloak:21.1.0 start-dev
```
Une fois le serveur lancé, vous pouvez vous rendre sur [http://localhost:8080](http://localhost:8080) et vous connecter avec les identifiants `admin` et `admin`. Vous pouvez ensuite créer un nouveau realm et le configurer comme vous le souhaitez.

Il vous faut ensuite copier le fichier .env.example dans le dossier `api-backend` et le renommer en .env. Ensuite, il faut modifier les variables d'environnement suivantes :
```bash
DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

KEYCLOAK_CLIENT_ID=
KEYCLOAK_CLIENT_SECRET=
KEYCLOAK_REALM_PUBLIC_KEY=
KEYCLOAK_BASE_URL=
KEYCLOAK_REALM=
KEYCLOAK_REDIRECT_URI=
KEYCLOAK_CACHE_OPENID=
```

Lancez ensuite la commande suivante pour installer les dépendances du projet :
```bash
composer install
```
puis lancez
```bash
php artisan key:generate
php artisan serve
```
Afin de générer la clé de chiffrement et de lancer le serveur.

Commande pour lancer les migrations et les seeders :
```bash
php artisan migrate:fresh --seed
```

## Lancer le frontend
Rendez vous dans le dossier `frontend` et lancez la commande suivante :
```bash
npm install
```
Une fois les dépendance installée, vous pouvez lancer le serveur avec la commande suivante :
```bash
npm run serve
```