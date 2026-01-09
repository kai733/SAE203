# Projet SAE203 - Gestion de Médiathèque

Ce projet est une application web de gestion de médiathèque comprenant une interface administrateur et une interface utilisateur. Elle permet de gérer des médias (livres, films, etc.), des emprunts et des utilisateurs.

## Structure du Projet

- **Site-admin** : Interface d'administration (gestion des médias, utilisateurs, emprunts).
- **Site-user** : Interface visiteur (consultation du catalogue).
- **BDD** : Scripts de base de données.

## Prérequis

- Un serveur web (Apache, Nginx, ou via XAMPP/WAMP/MAMP).
- PHP installé et configuré.
- Une base de données MySQL.

## Installation

### 1. Base de données

1. Ouvrez votre outil de gestion de base de données (ex: phpMyAdmin).
2. Importez le fichier `BDD/bdd203_update.sql`.
   - Ce script créera la base de données nommée `djepaxhk` et les tables nécessaires.

### 2. Configuration de la connexion

Les informations de connexion à la base de données sont définies directement dans les fichiers PHP. Vous devez les modifier pour correspondre à votre configuration locale (par exemple : utilisateur `root`, pas de mot de passe, host `localhost`).

**Attention** : Les blocs de connexion sont présents dans presque tous les fichiers PHP des dossiers `Site-admin` et `Site-user`. Il est nécessaire de modifier ces informations dans chaque fichier où elles apparaissent.

Recherchez le bloc de code suivant dans les fichiers PHP et adaptez les valeurs :

```php
$utilisateur = "votre_utilisateur_local";
$mdp = "votre_mot_de_passe";              
$base = "djepaxhk";                       
$serveur = "localhost";
```

### 3. Lancement

1. Placez le dossier du projet dans le répertoire racine de votre serveur web (ex: `htdocs` pour XAMPP, `www` pour WAMP).
2. Démarrez votre serveur web et MySQL.
3. Accédez aux interfaces via votre navigateur :
   - **Utilisateur** : `http://localhost/SAE203/Site-user/index-user.php`
   - **Administrateur** : `http://localhost/SAE203/Site-admin/index-admin.php`
