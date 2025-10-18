# 🌐 AHN CONNECT

AHN CONNECT est une **plateforme sociale académique** développée en **PHP**, destinée à favoriser la **communication, le partage de ressources et la collaboration** au sein d’un département universitaire.  
Elle combine les fonctionnalités d’un **réseau social**, d’un **espace d’apprentissage** et d’un **centre de ressources**.

---

## Présentation

L’objectif d’**AHN CONNECT** est de créer une communauté numérique dynamique reliant :
- les **étudiants**, pour échanger et partager leurs expériences ;
- les **enseignants**, pour publier des cours et annonces ;
- et le **département**, pour diffuser les informations et résultats officiels.

Ce projet a été conçu et développé par **Beidi Dina Samuel**, moniteur à l’Université de Maroua et formateur en informatique, dans un but pédagogique et communautaire.

---

## Fonctionnalités principales

### Espace étudiant
- Connexion et inscription via la table `etudiants`
- Gestion de profil (photo, informations, déconnexion)
- Ajout et gestion d’amis
- Messagerie privée entre amis

### Fil d’actualités
- Publication de posts (textes, images, fichiers)
- Likes et commentaires dynamiques (AJAX)
- Notifications en temps réel
- Partage d’informations du département

### Actualités du département
- Communiqués et événements officiels

### Discussions de groupe
- Envoi de messages en temps réel
- Espace de travail collaboratif (étudiants ↔ enseignants)

### Bibliothèque académique
- Archivage des anciens sujets, mémoires, rapports, TD et examens
- Téléchargement sécurisé des fichiers
- Organisation par filière et année académique

### Résultats de session
- Consultation sécurisée des notes par étudiant
- Interface simple et responsive

### À propos / Visite du département
- Présentation du département et de son historique
- Informations sur les responsables, contacts et partenaires
- Galerie de projets et réalisations des étudiants

---

## Technologies utilisées

| Catégorie | Outils / Technologies |
|------------|----------------------|
| **Frontend** | HTML5, CSS3, JavaScript, AJAX, Bootstrap |
| **Backend** | PHP (procédural & orienté objet) |
| **Base de données** | MySQL (XAMPP / phpMyAdmin) |
| **Serveur local** | Apache |
| **Langue** | Français |
| **Autres** | jQuery, Font Awesome, JSON, Sessions PHP |

---
## Installation et exécution

### 1. Cloner le dépôt
```bash
git clone https://github.com/samglish/app-ahn-connect.git
```
### 2. Placer le projet dans le dossier htdocs de XAMPP

C:\xampp\htdocs\ahn-connect

### 3. Créer la base de données
* Ouvre phpMyAdmin via (http://localhost/phpmyadmin)[http://localhost/phpmyadmin]
* Crée une base de données :
```sql
CREATE DATABASE ahnens9421_sam;
```
* Importe le fichier SQL fourni :
```pgsql
ahnens9421_enspm 2025.sql
```
### 4. Configurer la connexion

Dans le fichier : db.php
```sql
$host = 'localhost';      // Server name
$dbname = 'ahnens9421_enspm';  //database name
$username = 'ahnens9421_sam';       
$password = 'Samglish12';
```
```pgsql
ahn-connect/
│
├── config.php                   # Configuration de la base de données
├── index.php                    # Page principale (fil d’actualités)
│
├── includes/                    # Fichiers réutilisables
│   ├── header.php
│   ├── footer.php
│   ├── menu.php
│   └── functions.php
│
├── assets/                      # Ressources du site
│   ├── css/
│   ├── js/
│   └── img/
│
├── uploads/                     # Images et documents partagés
│
├── modules/                     # Modules principaux de l'application
│   ├── actualites/              # Actualités et publications
│   ├── amis/                    # Gestion des amis et messagerie
│   ├── discussions/             # Discussions de groupe
│   ├── resultats/               # Résultats académiques
│   ├── bibliotheque/            # Ressources et anciens sujets
│   └── apropos/                 # Présentation du département
│
├── notifications.php            # Notifications en temps réel
├── profil.php                   # Profil utilisateur
├── login.php / register.php     # Authentification
│
├── sql/
│   └── ahn_connect.sql          # Script de la base de données
│
├── LICENSE                      # Licence MIT
└── README.md                    # Documentation du projet
```
