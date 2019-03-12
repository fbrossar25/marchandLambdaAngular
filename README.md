# Projet WEB M2 ILC 2019

## TODO

- Accueil:
    - [ ] plusieurs articles (5)
    - [ ] /accueil
    - [ ] Redirection vers /catalogue/0
    - [ ] Redirection vers /article/{id}
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
    - [ ] Création client
    - [ ] Gestion d'erreur à l'inscription
- Compte Client
    - [ ] Édition info client
- Aministration
    - [ ] CRUD Article

## BONUS
- [ ] Panier
    - [ ] Ajout / Suppression / Consultation panier
    - [ ] Suppression d'un article -> suppression dans les paniers
- Paypal
    - [ ] Gestion paiement

## Commandes
 - Démarrer mysql : `mysql-ctl start`
 - Mise à jour des dépendances : `composer update`
 - Nettoyer cache Doctrine : `./doctrine orm:clear-cache:metadata`
 - Mettre à jour Entity depuis la base : `./doctrine orm:convert-mapping --namespace="" --force --from-database yml ./config/yaml`
 - Mettre à jour base depuis Entity : `./doctrine orm:schema-tool:update --force`
 - Valider schema : `./doctrine orm:validate-schema`
 - Générer Entity depuis la base : `./doctrine orm:generate-entities --no-backup --generate-annotations=false --update-entities=true --generate-methods=false ./src/model`

## Liens notables
 - PHPMyAdmin : https://m2ilc-2019-brossard-florian-fbrossar.c9users.io/phpmyadmin
 - Site du projet : https://m2ilc-2019-brossard-florian-fbrossar.c9users.io/home