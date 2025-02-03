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
