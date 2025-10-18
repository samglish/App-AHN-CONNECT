# 🌐 AHN CONNECT

AHN CONNECT est une **plateforme sociale académique** développée en **PHP**, conçue pour favoriser la **communication, la collaboration et le partage d’informations** entre étudiants, enseignants et responsables d’un département universitaire.

---

## Présentation

L’objectif principal d’**AHN CONNECT** est de créer un espace numérique dynamique permettant :
- aux étudiants de publier des actualités, liker et commenter ;
- aux enseignants de partager des annonces et supports pédagogiques ;
- au département de diffuser des informations officielles et d’interagir directement avec la communauté académique.

Ce projet a été initié et développé par **Beidi Dina Samuel**, moniteur à l’Université de Maroua, dans le cadre d’un projet de valorisation des compétences en développement web, systèmes et réseaux.

---

## Fonctionnalités principales

**Authentification sécurisée**  
- Connexion et inscription des étudiants via la table `etudiants`  
- Gestion de session et profil utilisateur (photo, nom, prénom)

**Fil d’actualités dynamique**  
- Publication de posts (textes, images, fichiers)  
- Likes et commentaires en temps réel (AJAX polling)  
- Affichage instantané sans rechargement de page  

**Notifications en temps réel**  
- Système de notification automatique pour likes et commentaires  
- Interface visuelle intuitive (icône de cloche avec compteur)

**Gestion du profil utilisateur**  
- Menu déroulant (dropdown) avec photo, nom et bouton de déconnexion  
- Modification des informations personnelles  

**Section d’actualités du département**  
- Publication officielle des communiqués et événements  
- Accès filtré selon le rôle (étudiant, enseignant, admin)

**Galerie des projets étudiants**  
- Présentation des travaux, objets 3D et créations artistiques  
- Valorisation des compétences et visibilité des productions du département

---

## Technologies utilisées

| Catégorie | Outils / Technologies |
|------------|----------------------|
| **Frontend** | HTML5, CSS3, JavaScript, AJAX |
| **Backend** | PHP (procédural & orienté objet) |
| **Base de données** | MySQL (via XAMPP / phpMyAdmin) |
| **Serveur local** | Apache (XAMPP) |
| **Langues** | Français |
| **Autres** | jQuery, Font Awesome, Bootstrap (responsive design) |

---

## Installation et exécution

### 🔹 1. Cloner le dépôt
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
