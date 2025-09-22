@echo off
echo Ce script va supprimer la base de données et la recréer via les Fixtures.

@REM Supprime la base de données
symfony console doctrine:database:drop --force
@REM Recréer la base de données
symfony console doctrine:database:create
@REM Supprimer tous les fichiers qui commencent par Ve dans migrations
del migrations\Ve*
@REM Génère une migration
symfony console make:migration --no-interaction
@REM Exécute les migrations
symfony console doctrine:migrations:migrate --no-interaction
@REM Charge les Fixtures
symfony console doctrine:fixtures:load --no-interaction

