#!/bin/bash

# Création des branches de base
git checkout -b dev
git push -u origin dev

# Liste des fonctionnalités (issues des User Stories)
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

# Création des branches feature depuis dev
for f in "${features[@]}"; do
  git checkout dev
  git checkout -b feature/$f
  git push -u origin feature/$f
done

# Retour sur dev
git checkout dev
