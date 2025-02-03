# Ina Zaoui - Projet 15 - Openclassrooms

## Description

Dernier projet du parcours Développeur d'application PHP/Symfony d'Openclassrooms.

## Dépendances

- PHP 8.0 ou supérieure
- Composer
- Symfony CLI
- MySQL ou un autre système de gestion de base de données compatible avec Doctrine

## Installation

1. Cloner le projet sur votre machine
2. Installer les dépendances avec Composer :```composer install```
3. Ajouter l'url de la base de données dans vos variables d'environnement (DATABASE_URL)

## Initialisation de la base de données

1. Créer la base de donnée :
   ```php bin/console doctrine:database:create```
2. Générer la migration :
   ```php bin/console make:migration```
3. Effectuer la migration :
   ```php bin/console doctrine:migrations:migrate```
4. Charger les fixtures :
   ```php bin/console doctrine:fixtures:load```

## Utilisation

Vous pouvez lancer un serveur en local ave la commande ```symfony serve```.

## Tests

Le projet est testé avec PHPUnit et phpStan. Voici les différentes commandes disponibles :

- ```composer unit``` : Permet de lancer les tests unitaires
- ```composer coverage``` : Permet de lance les tests unitaires et de générer un rapport de couverture de code
- ```composer stan``` : Permet de lancer phpStan pour vérifier la qualité du code

## Informations supplémentaires

- Un workflow github pour l'intégration continue est également présent. Il permet de lancer les tests unitaires et de
vérifier la qualité du code à chaque push sur la branche main.

- Le compte administrateur est créé lors du chargement des fixtures et les identifiants sont les suivants :
  - identifiant : `ina@zaoui.com`
  - mot de passe : `password`


------------------------------------------------------------------------------------------------------------------------

# Ina Zaoui - Project 15 - Openclassrooms

## Description

Last project of the PHP/Symfony Application Developer track on Openclassrooms.

## Dependencies

- PHP 8.0 or higher
- Composer
- Symfony CLI
- MySQL or another database management system compatible with Doctrine

## Installation

1. Clone the project on your machine
2. Install dependencies with Composer: ```composer install```
3. Add the database URL to your environment variables (DATABASE_URL)

## Database Initialization

1. Create the database:
   ```php bin/console doctrine:database:create```
2. Generate the migration:
   ```php bin/console make:migration```
3. Perform the migration:
   ```php bin/console doctrine:migrations:migrate```
4. Load the fixtures:
   ```php bin/console doctrine:fixtures:load```

## Usage

You can start a local server with the command ```symfony serve```.

## Tests

The project is tested with PHPUnit and phpStan. Here are the available commands:

- ```composer unit``` : Runs unit tests
- ```composer coverage``` : Runs unit tests and generates a code coverage report
- ```composer stan``` : Runs phpStan to check code quality

## Additional Information

- A GitHub workflow for continuous integration is also present. It runs unit tests and checks code quality on every push to the main branch.

- The admin account is created during the fixtures loading, and the credentials are as follows:
    - username: `ina@zaoui.com`
    - password: `password`
