# Projet WEB M2 ILC 2019

## TODO

- Accueil:
    - [x] plusieurs articles (6)
    - [x] /accueil
    - [x] Redirection vers /catalogue/1
    - [x] Redirection vers /article/{id}
- Catalogue
    - [x] Liste des article
    - [x] Nom, prix
    - [x] 9 par pages
    - [x] /catalogue/{page}
    - [x] Redirection vers /article/{id}
- Article
    - [x] Nom, descrption, prix
    - [ ] 15 Articles
- Recherche
    - [ ] filtrage Nom, prix
- Inscription
    - [x] Création client
    - [x] Gestion d'erreur à l'inscription
- Compte Client
    - [x] Connexion compte
    - [x] Édition info client
- Aministration
    - [x] Connexion compte admin
    - [x] Ajout article
    - [ ] Suppression article
    - [ ] Suppression compte client
- Sécurité
    - [ ] Filtrage des entrées utilisateurs
    
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