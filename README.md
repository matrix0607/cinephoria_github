🎬 Cinéphoria

Cinéphoria est une application web, mobile et bureautique dédiée aux passionnés de cinéma. Elle permet de consulter, réserver et gérer des séances de films, tout en offrant une interface intuitive pour les clients, les employés et les administrateurs.

📌 Description du projet

Ce projet a été réalisé dans le cadre de l'ECF et est composé de plusieurs modules :

Application web : consultation des films, réservation, espace client, espace employé, dashboard admin.

Application mobile (Flutter) : affichage des séances, QR code des billets, scan par les employés.

Application bureautique (Python + Tkinter) : gestion des incidents dans les salles.

API REST (Flask) : gestion des données, interfaçage avec MySQL et MongoDB.

⚙️ Fonctionnalités principales

🔍 Recherche et filtrage de films

🎟️ Réservation de séances

📱 QR code pour validation de billets

👤 Espace client et employé

🛠️ Gestion des incidents (application bureautique)

📊 Dashboard statistiques (MongoDB)

🔐 Authentification et gestion des rôles

🛠️ Technologies utilisées

Front-end web : PHP, HTML, CSS, JavaScript

Base de données : MySQL / MongoDB Atlas

Mobile : Flutter

Bureautique : Python + Tkinter

API : Flask

Serveur local : XAMPP (Apache + MySQL)

🧪 Installation locale avec XAMPP

Télécharger et installer XAMPP.

Cloner le dépôt dans le dossier htdocs :

git clone https://github.com/matth0607/cinephoria_github.git

🧪 Environnement de tests

L’application API Flask dispose d’un environnement de test complet :

✅ Tests unitaires

✅ Tests fonctionnels (endpoints API)

✅ Tests d’intégration (API + base MySQL)

⚙️ Préparation

Créer la base de données de test :

Accéder à http://localhost/phpmyadmin

Créer une base cinephoria_test

Importer cinephoria.sql puis insert_data.sql

Installer l’environnement Python :

python -m venv .venv
.venv\Scripts\activate  # Windows
source .venv/bin/activate  # Mac/Linux
pip install Flask mysql-connector-python pytest requests python-dotenv

⚙️ CI/CD

Pour automatiser les tests et les déploiements de Cinephoria, un workflow GitHub Actions peut être utilisé :

Intégration continue (CI) : compilation de l’APK, tests unitaires et fonctionnels automatisés.

Déploiement continu (CD) : mise à jour automatique de l’application après validation des tests.

Exemple de configuration GitHub Actions (.github/workflows/ci-cd.yml) :

name: CI/CD - Cinephoria

on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Set up JDK 11
        uses: actions/setup-java@v3
        with:
          distribution: 'temurin'
          java-version: '11'
      - name: Build APK
        run: ./gradlew assembleDebug
      - name: Run Tests
        run: ./gradlew test

  deploy:
    needs: build
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Upload APK to GitHub Releases
        uses: softprops/action-gh-release@v1
        with:
          files: app/build/outputs/apk/debug/app-debug.apk
        env:
          GITHUB_TOKEN: ${{ secrets.GITHUB_TOKEN }}


Avantages :

Réduction des erreurs humaines

Déploiement plus rapide et fiable
