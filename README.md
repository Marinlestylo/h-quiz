# Portail de quiz et drill pour examens sur ordinateur

## Introduction
Ce projet est réalisé dans le cadre de mon travail de Bachelor dans la Haute École d'Ingénierie et de Gestion du Canton de Vaud (HEIG-VD). Il a pour but de refactor le code d'une plateforme de quiz interactive existante afin de la rendre plus maintenable et évolutive. Il doit également ajouter quelques fonctionnalités comme la possibilité de faire des exercices de type "drill" et de pouvoir faire des examens sur ordinateur.

Vous pouvez trouver le repository du projet original [ici](https://github.com/heig-vd-tin/heig-quiz).

Le projet est actuellement hébergé [ici](https://h-quiz.heig-vd.site/).

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

Si vous êtes sur un système d'exploitation Windows, vous aurez également besoin de [WSL 2](https://learn.microsoft.com/fr-fr/windows/wsl/install) et la version [22.04.2 de Ubuntu](https://apps.microsoft.com/store/detail/ubuntu-22042-lts/9PN20MSR04DW?hl=fr-ch&gl=ch&rtc=1). Vous pouvez le télécharger en suivant les liens.

### PHP
Installez ensuite la version de PHP qui vous convient en suivant ce lien : [guide d'installation de php](https://tecadmin.net/how-to-install-php-on-ubuntu-22-04/).

Activez les exentions php-curl, php-xml et de mysql grace à la commande suivante: 
```bash
sudo apt-get install php-curl php-xml php-mysql
```

### Composer
Installez composer en suivant ce lien : [guide d'installation de composer](https://getcomposer.org/download/).

Pour activer composer globalement, il faut également faire la commande suivante:
```bash
sudo mv composer.phar /usr/local/bin/composer
```

### Node et NPM
Installez Node et NPM en suivant l'option 2 de ce guide : [guide d'installation de Node.js et NPM](https://www.digitalocean.com/community/tutorials/how-to-install-node-js-on-ubuntu-22-04#option-2-installing-node-js-with-apt-using-a-nodesource-ppa)


## Cloner le projet
Ce projet a été développé dans l'environnement WSL 2 avec la version `22.04.2` de Ubuntu. Il n'est donc pas garanti qu'il fonctionne correctement sur Windows sans WSL.

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
Si vous n'avez pas de serveur keycloak, vous pouvez en lancer un grâce à la commande suivante :
```bash
docker run -p 8080:8080 -e KEYCLOAK_ADMIN=admin -e KEYCLOAK_ADMIN_PASSWORD=admin quay.io/keycloak/keycloak:21.1.0 start-dev
```
Une fois le serveur lancé, vous pouvez vous rendre sur [http://localhost:8080](http://localhost:8080) et vous connecter avec les identifiants `admin` et `admin`. Vous pouvez ensuite créer un nouveau realm et le configurer comme vous le souhaitez.

### Compilation du code
Afin que les étudiants puissent compiler leur code durant les quiz, j'utilise le [RemoteCodeCompiler](https://github.com/zakariamaaraki/RemoteCodeCompiler) de [zakariamaaraki](https://github.com/zakariamaaraki).

Lors du développement de ce projet, j'utilise la version du code liée à ce [commit](https://github.com/zakariamaaraki/RemoteCodeCompiler/commit/ecfa068bcc87dee799c31063470281b6e48fa976). Il est fort probable que le code soit mis à jour dans le futur et il se pourrait que les instructions qui suivent ne fonctionnent plus. Si c'est le cas, je vous invite à reprendre le code au commit indiqué ci-dessus.

Une fois le projet cloné, vous devez exécuter ces commandes extraites de la documentation du projet:

1- Build a docker image:

```shell
docker image build . -t compiler
```

2- Create a volume:

```shell
docker volume create compiler
```

3- build the necessary docker images used by the compiler

```shell
./environment/build.sh
```

4- Run the container:

```shell
docker container run -p 8080:8082 -v /var/run/docker.sock:/var/run/docker.sock -v compiler:/compiler -e DELETE_DOCKER_IMAGE=true -e EXECUTION_MEMORY_MAX=10000 -e EXECUTION_MEMORY_MIN=0 -e EXECUTION_TIME_MAX=15 -e EXECUTION_TIME_MIN=0 -e MAX_REQUESTS=1000 -e MAX_EXECUTION_CPUS=0.2 -e COMPILATION_CONTAINER_VOLUME=compiler -t compiler
```

> **Note**  
> Il est conseillé de lancer ce docker dans WSL si vous travaillez sur Windows.

### Configuration du .env

Il vous faut ensuite copier le fichier `.env.example` dans le dossier `api-backend` et le renommer en `.env`. Ensuite, il faut modifier les variables d'environnement suivantes :
```bash
APP_URL=

DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

KEYCLOAK_CLIENT_ID=
KEYCLOAK_CLIENT_SECRET=(optionnel)
KEYCLOAK_REALM_PUBLIC_KEY=(optionnel)
KEYCLOAK_BASE_URL=
KEYCLOAK_REALM=
KEYCLOAK_REDIRECT_URI=
KEYCLOAK_CACHE_OPENID=(optionnel)

CODE_COMPILER_URL=
```

Lancez ensuite la commande suivante pour installer les dépendances du projet :
```bash
composer install
```
Commande pour générer la clé de chiffrement :
```bash
php artisan key:generate
```


Commande pour lancer les migrations et les seeders :
```bash
php artisan migrate:fresh --seed
```

Commande pour lancer le serveur :
```bash
php artisan serve
```

Le backend tourne mainantenant sur le port 8000.

## Lancer le frontend
Rendez vous dans le dossier `frontend` et lancez la commande suivante :
```bash
npm install
```
Une fois les dépendance installées, vous pouvez lancer le serveur avec la commande suivante :
```bash
npm run serve
```
Le frontend tourne mainantenant sur le port 5173.

Maintenant que le frontend et le backend sont lancés, vous pouvez vous rendre sur [http://localhost:5173](http://localhost:5173) et vous connecter.

> **Note**  
> Si vous mettez AUTOLOGIN=true dans le fichier .env du backend, vous serez automatiquement connecté avec le compte utilisateur ayant l'id n°1. Cela peut être utile pour le développement si vous n'avez pas de serveur Keycloak. Vous pouvez modifier ce comportement en allant dans le fichier `/api-backend/app/Providers/AppServiceProvider.php`.

## Déployement du projet
Pour déployer le projet, il vous faudra ``build`` le frontend et le mettre dans le dossier `public` du backend. 

Pour build, vous pouvez utiliser les commandes suivantes :
```bash
cd frontend
npm run build
```
Il vous faut ensuite copier le contenu du dossier `dist` dans le dossier `public` du backend.

Dans le dossier `frontend` se trouve un fichier .env.production. Il ne contient que deux variables qui sont les urls du ``frontend`` et du `backend`. Vous devrez les modifier pour qu'elles correspondent à votre environnement avant de build le ``frontend``.
Ce n'est jamais une bonnée idée de mettre des fichiers `.env` sur github. Cependant, ce dernier ne contient aucune information sensible et il permet un déployement automatique via des actions github. C'est pourquoi je l'ai laissé dans le projet.

## Workflow
Ce projet utilise deux branches : `main` et `dev`. La branche `main` est la branche de production. La branche `dev` est la branche de développement. Toutes les fonctionnalités sont développées sur la branche `dev` et une fois qu'un certains nombres d'entre elles sont terminées et qu'il n'y a pas d'erreur, elles sont mergées sur la branche `main` via des Pull Request.

> **Note**  
> La branche `main` n'est pas protégée. Il est donc possible de push directement dessus. Cependant, il est conseillé de ne pas le faire.

## Github actions
Ce projet utilise deux github actions bien différentes afin de former un pipeline CI/CD.

La première github action est déclenchée à chaque Pull Request sur la branche main ou à chaque push sur cette dernière. Elle permet de faire quelques tests basiques sur l'API de l'application.

La deuxième github action est déclenchée à chaque push sur la branche main. Elle permet de build le frontend et de le mettre dans le dossier `public` du backend. Elle permet également de déployer le backend sur un serveur distant grâce à des githubs secrets.