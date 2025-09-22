#!/bin/bash

# --- Configuration ---
PROJECT_NAME="Cinephoria"
REMOTE_URL="<URL_DE_TON_DEPOT>"  # Remplace par ton URL GitHub ou GitLab

# --- Initialisation du dépôt ---
echo "Initialisation du dépôt Git pour $PROJECT_NAME..."
git init
git add .
git commit -m "Initial commit: ajout du projet $PROJECT_NAME"

# Renommer la branche principale en main
git branch -M main

# Ajouter le remote
git remote add origin $REMOTE_URL
git push -u origin main

# --- Création de la branche de développement ---
git checkout -b dev
git push -u origin dev

# --- Liste des fonctionnalités (issues des User Stories) ---
features=(
  "menu-site"
  "page-accueil"
  "bas-de-page"
  "reservation"
  "films"
  "compte-utilisateur"
  "admin-espace"
  "employe-espace"
  "utilisateur-espace"
  "contact"
  "mobile-seances"
  "bureautique-incidents"
)

# --- Création des branches feature et commits simulés ---
for f in "${features[@]}"; do
  git checkout dev
  git checkout -b feature/$f
  
  # Création d'un fichier simulant la fonctionnalité
  echo "// Code pour la fonctionnalité $f" > $f.txt
  git add $f.txt
  git commit -m "feat: ajout de la fonctionnalité $f"
  
  git push -u origin feature/$f
  
  # Merge automatique dans dev
  git checkout dev
  git merge --no-ff feature/$f -m "merge: $f dans dev"
  
  # Supprimer la branche feature localement (optionnel)
  git branch -d feature/$f
done

# --- Merge final dans main ---
git checkout main
git merge --no-ff dev -m "merge: dev dans main"
git push

echo "✅ Dépôt Cinephoria prêt avec toutes les fonctionnalités intégrées dans main"
