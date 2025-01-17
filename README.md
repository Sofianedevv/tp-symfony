Prérequis

Base de données : MySQL
Outils nécessaires :
Composer
Symfony CLI

Installation:
Cloner le projet
    git clone https://github.com/Sofianedevv/tp-symfony.git  

Installer les dépendances
    composer install  
    
Modifier les paramètres nécessaires dans le fichier .env si besoin, en particulier la connexion à la base de données :

DATABASE_URL="mysql://root:root@127.0.0.1:3306/exercices"  

Créer la base de données
    php bin/console doctrine:database:create  
    
Charger les fixtures
    php bin/console doctrine:fixtures:load  
    
Démarrer le projet
Lancer le serveur Symfony :
symfony server:start  


Identifiant admin : email: 'admin@example.com'  password: 'jesuisadmin'
Identifiant user: email: 'user1@example.com' password: 'password123'
Identifiant banned: email: 'banned@example.com' password: 'bannis'


Le projet sera accessible à l'adresse suivante : http://localhost:8000.








Fonctionnalités :
    Connexion/inscription
    Système de recherche
    Système de notifications (lorsqu'un nouveau sujet, chapitre ou exercice est ajouté)
    Page de profil
    Modification du profil
    Modification du mot de passe
    Ajout, modification et suppression des sujets, chapitres et exercices par l'administrateur
    Publication de commentaires
    Suppression de commentaires par l'administrateur
    Gestion des utilisateurs par l'administrateur, avec possibilité de bannir ou débannir
    Mode nuit
    Système de favoris
    Mot de passe oublié/réinitialisation via email
    Page de contact
    Gestion des emails avec Mailtrap
    Pagination


Entités: 
    Chapter
    Exercice
    Subject
    User
    Comment
    Contact
    Notifications
