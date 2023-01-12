# Pokemon Memory Game

Un jeu de memory avec des pokemon.

## Pré-requis

- LAMP :
    - Linux core OS
    - Apache ^2.4
    - MySQL ^8.1
    - PHP ^8.1
- Symfony CLI ^5.4
- NodeJS ^18.12.1
- Symfony ^6.1.7

## Installation

1. cloner le repository
1. Configurer la BDD dans le `.env.local`
1. Installer les dépendances backend du projet : `composer i`
1. Installer les dépendances frontend du projet : `npm i`
1. Créer et modifier les droits du dossier build :
```
mkdir /var/www/html/public/build
chmod 777 /var/www/html/public/build
```
1. Construire les assets : `npm run build`
1. Initialiser la base de données :
    - `php bin/console doctrine:database:create` (pour créer la base de données)
    - `php bin/console doctrine:migrations:migrate` (pour créer les tables)
    - `php bin/console doctrine:fixtures:load` (pour insérer les données initiales)
    - `php bin/console cache:clear` (pour réinitialiser le cache)