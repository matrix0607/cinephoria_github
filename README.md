
# 🎬 Cinéphoria

Cinéphoria est une application web, mobile et bureautique dédiée aux passionnés de cinéma. Elle permet de consulter, réserver et gérer des séances de films, tout en offrant une interface intuitive pour les clients, les employés et les administrateurs.

---

## 📌 Description du projet

Ce projet a été réalisé dans le cadre de l'ECF. Il est composé de plusieurs modules :

- **Application web** : consultation des films, réservation, espace client, espace employé, dashboard admin.
- **Application mobile (Flutter)** : affichage des séances, QR code des billets, scan par les employés.
- **Application bureautique (Python + Tkinter)** : gestion des incidents dans les salles.
- **API REST (Flask)** : gestion des données, interfaçage avec MySQL et MongoDB.

---

## ⚙️ Fonctionnalités principales

- 🔍 Recherche et filtrage de films
- 🎟️ Réservation de séances
- 📱 QR code pour validation de billets
- 👤 Espace client et employé
- 🛠️ Gestion des incidents (application bureautique)
- 📊 Dashboard statistiques (MongoDB)
- 🔐 Authentification et gestion des rôles

---

## 🛠️ Technologies utilisées

- PHP / HTML / CSS / JavaScript
- MySQL / MongoDB Atlas
- Flutter (mobile)
- Python + Tkinter (bureau)
- Flask (API)
- XAMPP (Apache + MySQL)

---

## 🧪 Installation locale avec XAMPP

1. Télécharger et installer XAMPP
2. Cloner ce dépôt dans le dossier `htdocs` :
   ```bash
   git clone https://github.com/matth0607/cinephoria_github.git
   ```

## 🧪 Environnement de tests

L’application **API Flask** dispose d’un environnement de test complet avec :
- ✅ Tests unitaires
- ✅ Tests fonctionnels (endpoints API)
- ✅ Tests d’intégration (API + base MySQL)

### ⚙️ Préparation

1. **Créer la base de données de test**
   - Aller sur [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
   - Créer une base `cinephoria_test`.
   - Importer `cinephoria.sql` puis `insert_data.sql`.

2. **Installer l’environnement Python**
   ```bash
   python -m venv .venv
   .venv\Scripts\activate
   pip install Flask mysql-connector-python pytest requests python-dotenv
