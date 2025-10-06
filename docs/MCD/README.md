# 🗺️ Modèle Conceptuel de Données (MCD) - Cinéphoria

Ce schéma représente l’organisation logique des données du projet **Cinéphoria**.

## 🎯 Objectif
Le MCD permet de visualiser les relations entre les différentes entités du système : films, utilisateurs, réservations, séances, salles, etc.

## 🧩 Entités principales
- **Utilisateur** : gère les comptes clients, employés et administrateurs.  
- **Film** : contient les informations sur les films (titre, genre, durée, note…).  
- **Salle** : représente les salles de cinéma avec leur capacité.  
- **Séance** : relie un film et une salle à une date précise.  
- **Réservation** : enregistre les achats effectués par les utilisateurs.  
- **Commande** : regroupe les réservations d’un utilisateur.  
- **Avis / Notes** : permettent aux utilisateurs de donner leur opinion sur les films.  
- **Incident** : rapporte un problème survenu dans une salle.

## 🔗 Relations principales
- Un **utilisateur** peut faire plusieurs **réservations**.  
- Une **réservation** concerne une seule **séance** (film + salle).  
- Un **film** peut être projeté dans plusieurs **cinémas** (relation N:N via FILM_CINEMA).  
- Un **film** appartient à un **genre**.  
- Une **salle** appartient à un **cinéma**.  
- Des **incidents** peuvent être liés à une salle spécifique.

## 📂 Fichier du schéma
📄 [MCD_Cinephoria.pdf](./MCD_Cinephoria.pdf)
