# Gestionnaire de Notes pour Étudiants

**Description**

Le gestionnaire de notes pour étudiants est une application développée en utilisant Symfony et PHP pour simplifier le suivi des performances académiques des élèves. L'objectif principal de ce projet est de fournir aux enseignants un moyen efficace de noter et de commenter les performances des élèves tout au long de l'année scolaire.

**Fonctionnalités Principales**

- **Fiches Récapitulatives des Notes :** L'application offre une interface conviviale permettant aux enseignants de consulter rapidement et facilement les fiches récapitulatives des notes de chaque élève.

- **Commentaires Personnalisés :** Les enseignants peuvent ajouter des commentaires personnalisés à chaque note, fournissant ainsi aux élèves des retours détaillés sur leurs performances et des conseils pour s'améliorer.

- **Suivi Continu :** Grâce à la fonction de suivi continu, les enseignants peuvent enregistrer les notes au fil du temps, offrant une vision complète de la progression académique de chaque élève.

**Problème Résolu / Besoin Comblé**

Ce gestionnaire de notes résout le problème de gestion manuelle fastidieuse des notes des élèves. Il comble le besoin de centraliser toutes les informations de notation, offrant ainsi  aux enseignants un outil efficace pour suivre et évaluer les progrès académiques.

**Technologies Utilisées**

Ce projet est développé en utilisant le framework Symfony et le langage de programmation PHP, garantissant une base solide et sécurisée pour la gestion des données académiques.

---

**Prérequis**

Avant de commencer, assurez-vous d'avoir installé les éléments suivants :

- [PHP 8.2](https://www.php.net/downloads) (curl, fileinfo, gd, intl, mbstring, openssl, pdo_sqlite)
- [Symfony-cli](https://symfony.com/download) (optionnel)

**Installation**

```bash
git clone https://github.com/balkent/RatingM1.git
cd RatingM1
symfony console doctrine:migrations:migrate
```
En dev, pour avoir des données, il faut créer les fichiers **_studentsData.json, subjectsData.json, supplement.json, supplementTypes.json_** dans **scr/DataFixtures**.

Des exemples sont dans les fichiers respectives en PHP du même nom, puis lancer la commande suivante:

```bash
symfony console doctrine:fixtures:load
```

**Utilisation**

```bash
npm run build
symfony serve
``` 
