# Projet WEB M2 ILC 2019

## TODO

- Accueil:
    - [x] plusieurs articles (6)
    - [x] /accueil
    - [ ] Redirection vers /catalogue/0
    - [x] Redirection vers /article/{id}
- Catalogue
    - [ ] Liste des article
    - [ ] Nom, prix
    - [ ] 20 par pages
    - [ ] /catalogue/{page}
    - [ ] Redirection vers /article/{id}
- Article
    - [ ] Nom, descrption, prix
    - [ ] /article/{id}
- Recherche
    - [ ] filtrage Nom, prix
- Inscription
    - [x] Création client
    - [x] Gestion d'erreur à l'inscription
- Compte Client
    - [x] Connexion compte
    - [ ] Édition info client
- Aministration
    - [x] Connexion compte admin
    - [x] Ajout article
    - [ ] Suppression article
    - [ ] Suppression compte client

## BONUS
- Panier
    - [ ] Ajout / Suppression / Consultation panier
    - [ ] Suppression d'un article -> suppression dans les paniers
- Paypal
    - [ ] Gestion paiement

## Commandes
 - Démarrer mysql : `mysql-ctl start`
 - Mise à jour des dépendances : `composer update`
 - Nettoyer cache Doctrine : `./doctrine orm:clear-cache:metadata`
 - Mettre à jour mapping depuis la base : `./doctrine orm:convert-mapping --namespace="" --force --from-database yml ./config/yaml`
 - Mettre à jour base depuis Entity : `./doctrine orm:schema-tool:update --force`
 - Valider schema : `./doctrine orm:validate-schema`
 - Générer Entity depuis la base : `./doctrine orm:generate-entities --no-backup --generate-annotations=false --update-entities=true --generate-methods=false ./src/model`

## Liens notables
 - PHPMyAdmin : https://m2ilc-2019-brossard-florian-fbrossar.c9users.io/phpmyadmin
 - Site du projet : https://m2ilc-2019-brossard-florian-fbrossar.c9users.io/home