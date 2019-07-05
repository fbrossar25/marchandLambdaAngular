# Projet WEB M2 ILC 2019

## Comptes admin
 - login : admin@test.fr
 - pass : azerty

## Commandes
 - Démarrer mysql : `mysql-ctl start`
 - Mise à jour des dépendances : `composer update`
 - Nettoyer cache Doctrine : `./doctrine orm:clear-cache:metadata`
 - Mettre à jour mapping depuis la base : `./doctrine orm:convert-mapping --namespace="" --force --from-database yml ./config/yaml`
 - Mettre à jour base depuis Entity : `./doctrine orm:schema-tool:update --force`
 - Valider schema : `./doctrine orm:validate-schema`
 - Générer Entity depuis la base : `./doctrine orm:generate-entities --no-backup --generate-annotations=false --update-entities=true --generate-methods=false ./src/model`
