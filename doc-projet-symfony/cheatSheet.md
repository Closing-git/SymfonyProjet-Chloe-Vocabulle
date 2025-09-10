# CHEAT SHEET

## Serveur

```
symfony serve
symfony server:start
```

## Après clonage d'un repo

```
composer install
(si dépendances JS : npm install)
```

## GIT

```
git add .
git commit -m ""
git remote add origin https://urldugit ...
*Pour delete*
git remote remove origin
```

## Symfony

### Config le lien à la db

```
*Après avoir configuré le fichier .env avec la connexion, en modifiant cette ligne :*
(DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=10.11.2-MariaDB&charset=utf8mb4")
```

### Télécharger les packages pour l'ORM

```
symfony composer req symfony/orm-pack
symfony composer req symfony/maker-bundle --dev
```

### Création de la DB

```
symfony console doctrine:database:create
```

### Créer une migration et la lancer

```
symfony console make:migration
symfony console doctrine:migrations:migrate
```