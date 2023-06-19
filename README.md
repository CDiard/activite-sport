# SUAPS 🏃‍♂️ - README 📝

SUAPS est un projet basé sur Symfony, qui vise à fournir une solution de gestion des teams building pour le SUAPS. Ce README vous guidera à travers le processus d'installation du projet sur votre environnement local ou un environnement en production.

## Prérequis 📋

Avant de commencer, assurez-vous que votre système dispose des éléments suivants :

    PHP 8.0 ou version supérieure
    Extension PHP requise : intl
    Composer (https://getcomposer.org)
    MySQL / MariaDB / PhpMyAdmin
    Yarn

## Installation 💻

Suivez les étapes ci-dessous dans l'ordre pour installer et configurer SUAPS sur votre machine :

#### Clonez le dépôt GitHub dans le répertoire souhaité :

`git clone https://github.com/CDiard/activite-sport.git`

#### Accédez au répertoire du projet :

`cd activite-sport`

#### Installez les dépendances du projet en utilisant Composer :

`composer install`

#### Installez les dépendances du projet en utilisant Yarn :

`yarn install`

#### Copiez le fichier d'exemple .env et renommez-le en .env.local :

`cp .env .env.local`

#### Ouvrez le fichier .env.local dans un éditeur de texte et configurez les paramètres de connexion à votre base de données :

`DATABASE_URL="mysql://INDENTIFIANT:MOTDEPASSE@127.0.0.1:3306/activite-sport?serverVersion=mariadb-10.5.4"`

Remplacez INDENTIFIANT et MOTDEPASSE par vos informations d'identification, et activite-sport par le nom de la base de données que vous souhaitez utiliser.

#### Créez la base de données en exécutant la commande suivante :

`php bin/console doctrine:database:create`

#### Générez les tables de la base de données en exécutant les migrations :

`php bin/console doctrine:migrations:migrate`

#### Créer un compte enseignant dans la base de données :

`php bin/console security:hash-password MOTDEPASSE`

Replacez MOTDEPASSE par le mot de passe de votre choix, puis copier la valeur de "Password hash" (le hash), quelque chose comme ça  :  
`$2y$13$EUf4E0qs3tt5y1iimnn02uWWPI3HXhnVmB6lGBoA9Ifv9iZ2uO6QS`

Une fois cette commande réalisé vous pouvez aller dans la table USER de votre base de donnée.  
Ajoutez une nouvelle entrée à cette table en allant dans l'onglet "Insérer" de PhpMyAdmin (ou via ligne de commande MySQL) :  
* username : `INDENTIFIANT`
* role : `[]`
* password : `HASH`  

Remplacez INDENTIFIANT par l'identifiant de votre choix (admin ou autre) et HASH par le "Password hash" copier juste avant et appuyez sur le bouton "Exécuter" pour ajouter la ligne.

#### Ouvrez le fichier .env.local dans un éditeur de texte et configurez si vous êtes en production ou en développement :

Pour un environnement de production :  
`APP_ENV=prod`  
Pour un environnement de développement :  
`APP_ENV=dev`  

#### Préparer les ressources avec Yarn :

Pour un environnement de production :  
`yarn run build`  
Pour un environnement de développement :  
`yarn run dev`

#### Démarrez le serveur de développement Symfony :

`symfony server:start`

Le serveur démarrera à l'adresse http://localhost:8000. Ouvrez votre navigateur et accédez à l'URL http://localhost:8000. Vous devriez voir l'application SUAPS en cours d'exécution.  
Pour vous connecter à la partie enseignant vous pouvez aller à l'adresse http://localhost:8000/prof et entrer l'identifiant et le mot de passe enregistré plus tôt.

## Félicitations ! 🦾

Vous avez installé avec succès le projet SUAPS sur votre machine. N'hésitez pas à explorer les fonctionnalités de l'application et à l'adapter selon vos besoins.

## Problèmes ⚠

Si vous rencontrez des problèmes lors de l'installation ou de l'utilisation de SUAPS, veuillez ouvrir un rapport d'incident dans la section "Issues" du dépôt GitHub. Nous ferons de notre mieux pour vous aider à résoudre les problèmes rencontrés.
