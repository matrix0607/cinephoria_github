-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 21 août 2025 à 16:14
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `cinephoria`
--

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `film_id` int(11) DEFAULT NULL,
  `note` int(11) DEFAULT NULL CHECK (`note` between 1 and 5),
  `commentaire` text DEFAULT NULL,
  `valide` tinyint(1) DEFAULT 0,
  `date_avis` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cinemas`
--

CREATE TABLE `cinemas` (
  `id` int(11) NOT NULL,
  `ville` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cinemas`
--

INSERT INTO `cinemas` (`id`, `ville`) VALUES
(1, 'Paris'),
(2, 'Strasbourg'),
(3, 'Marseille');

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `seance_id` int(11) NOT NULL,
  `date_commande` datetime DEFAULT current_timestamp(),
  `statut` varchar(50) DEFAULT 'confirmée'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `films`
--

CREATE TABLE `films` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `age_minimum` int(11) DEFAULT NULL,
  `coup_de_coeur` tinyint(1) DEFAULT 0,
  `note` float DEFAULT 0,
  `affiche` varchar(255) DEFAULT NULL,
  `date_ajout` date NOT NULL,
  `genre_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `films`
--

INSERT INTO `films` (`id`, `titre`, `description`, `age_minimum`, `coup_de_coeur`, `note`, `affiche`, `date_ajout`, `genre_id`) VALUES
(1, 'Inception', 'Un thriller de science-fiction réalisé par Christopher Nolan.', 12, 1, 4.8, 'inception.jpg', '2025-07-30', 1),
(2, 'Le Roi Lion', 'Un classique de Disney pour toute la famille.', 6, 0, 4.5, 'lionking.jpg', '2025-07-23', 2),
(3, 'Interstellar', 'Un voyage épique à travers l’espace et le temps.', 10, 1, 4.9, 'interstellar.jpg', '2025-07-23', 1),
(4, 'Les Étoiles de Minuit', 'Un drame spatial sur une mission de sauvetage intergalactique.', 10, 1, 4.7, 'etoiles_minuit.jpg', '2025-07-23', 2),
(5, 'Le Secret des Abysses', 'Un thriller sous-marin où une équipe découvre une civilisation oubliée.', 12, 0, 4.3, 'abysses.jpg', '2025-07-23', 1),
(6, 'Rires en Cascade', 'Une comédie familiale pleine de rebondissements et de quiproquos.', 6, 1, 4.5, 'rires.jpg', '2025-07-23', 2),
(7, 'L\'Ombre du Temps', 'Un film de science-fiction sur les paradoxes temporels.', 14, 0, 4.2, 'ombre_temps.jpg', '2025-07-23', 1),
(8, 'Cœurs Rebelles', 'Une romance entre deux artistes dans un Paris contemporain.', 12, 1, 4.6, 'coeurs_rebelles.jpg', '2025-07-23', 1),
(9, 'La Forêt Interdite', 'Un film fantastique où des enfants découvrent un monde magique.', 8, 0, 4.1, 'foret_interdite.jpg', '2025-07-23', 2),
(10, 'Justice Noire', 'Un polar haletant dans les rues de Marseille.', 16, 0, 4, 'justice_noire.jpg', '2025-07-23', 1),
(11, 'Pixel War', 'Une aventure numérique dans un monde de jeux vidéo rétro.', 10, 1, 4.4, 'pixel_war.jpg', '2025-07-23', 1);

-- --------------------------------------------------------

--
-- Structure de la table `film_cinema`
--

CREATE TABLE `film_cinema` (
  `id_film` int(11) NOT NULL,
  `id_cinema` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `film_cinema`
--

INSERT INTO `film_cinema` (`id_film`, `id_cinema`) VALUES
(1, 1),
(2, 3),
(3, 2),
(4, 1),
(5, 1),
(6, 3),
(7, 2),
(8, 3),
(9, 2),
(10, 2);

-- --------------------------------------------------------

--
-- Structure de la table `genres`
--

CREATE TABLE `genres` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `genres`
--

INSERT INTO `genres` (`id`, `nom`) VALUES
(1, 'Action'),
(2, 'Comédie');

-- --------------------------------------------------------

--
-- Structure de la table `incidents`
--

CREATE TABLE `incidents` (
  `id` int(11) NOT NULL,
  `salle_id` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `date_creation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `incidents`
--

INSERT INTO `incidents` (`id`, `salle_id`, `description`, `date_creation`) VALUES
(1, '1', 'siege 2', '2025-08-21 14:06:46');

-- --------------------------------------------------------

--
-- Structure de la table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `film_id` int(11) NOT NULL,
  `note` tinyint(4) NOT NULL CHECK (`note` between 1 and 5),
  `description` text DEFAULT NULL,
  `date_note` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `places_reservees`
--

CREATE TABLE `places_reservees` (
  `id` int(11) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  `numero_place` int(11) DEFAULT NULL,
  `mobilite_reduite` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `places_reservees`
--

INSERT INTO `places_reservees` (`id`, `reservation_id`, `numero_place`, `mobilite_reduite`) VALUES
(1, 1, 2, 0),
(2, 2, 3, 0),
(3, 3, 3, 0),
(4, 4, 4, 0);

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  `seance_id` int(11) DEFAULT NULL,
  `nombre_personnes` int(11) DEFAULT NULL,
  `prix_total` decimal(6,2) DEFAULT NULL,
  `date_reservation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id`, `utilisateur_id`, `seance_id`, `nombre_personnes`, `prix_total`, `date_reservation`) VALUES
(1, 1, 1, 1, 10.00, '2025-07-30 14:59:58'),
(2, 1, 4, 1, 10.00, '2025-07-30 15:26:39'),
(3, 1, 2, 1, 10.00, '2025-08-06 09:34:03'),
(4, 1, 15, 1, 10.00, '2025-08-06 09:39:29');

-- --------------------------------------------------------

--
-- Structure de la table `salles`
--

CREATE TABLE `salles` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `nombre_places` int(11) DEFAULT NULL,
  `qualite_projection` varchar(50) DEFAULT NULL,
  `cinema_id` int(11) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `salles`
--

INSERT INTO `salles` (`id`, `nom`, `nombre_places`, `qualite_projection`, `cinema_id`, `ville`) VALUES
(1, 'Salle Alpha', 120, '4K', 1, 'Strasbourg'),
(2, 'Salle Lumière', 100, 'HD', 1, 'Strasbourg'),
(3, 'Salle Étoile', 90, '4DX', 2, 'Paris'),
(4, 'Salle Gaumont', 150, 'IMAX', 2, 'Paris'),
(5, 'Salle Méditerranée', 110, 'HD', 3, 'Marseille'),
(6, 'Salle Provence', 80, '4K', 3, 'Marseille');

-- --------------------------------------------------------

--
-- Structure de la table `seances`
--

CREATE TABLE `seances` (
  `id` int(11) NOT NULL,
  `film_id` int(11) DEFAULT NULL,
  `salle_id` int(11) DEFAULT NULL,
  `date_heure_debut` datetime DEFAULT NULL,
  `date_heure_fin` datetime DEFAULT NULL,
  `qualite` varchar(50) DEFAULT NULL,
  `cinema_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `seances`
--

INSERT INTO `seances` (`id`, `film_id`, `salle_id`, `date_heure_debut`, `date_heure_fin`, `qualite`, `cinema_id`) VALUES
(1, 1, 1, '2025-08-01 18:00:00', '2025-08-01 20:00:00', '4K', 1),
(2, 2, 2, '2025-08-02 19:00:00', '2025-08-02 21:00:00', '3D', 3),
(3, 3, 1, '2025-08-03 20:00:00', '2025-08-03 22:00:00', '4DX', 2),
(4, 1, 2, '2025-08-04 21:00:00', '2025-08-04 23:00:00', 'HD', 1),
(5, 2, 1, '2025-08-05 22:00:00', '2025-08-06 00:00:00', '4K', 3),
(6, 3, 2, '2025-08-06 23:00:00', '2025-08-07 01:00:00', '3D', 2),
(7, 1, 1, '2025-08-08 00:00:00', '2025-08-08 02:00:00', '4DX', 1),
(8, 2, 2, '2025-08-09 01:00:00', '2025-08-09 03:00:00', 'HD', 3),
(9, 3, 1, '2025-08-10 02:00:00', '2025-08-10 04:00:00', '4K', 2),
(10, 1, 2, '2025-08-11 03:00:00', '2025-08-11 05:00:00', '3D', 1),
(11, 1, 1, '2025-08-02 15:15:00', '2025-08-02 17:15:00', 'HD', 1),
(12, 1, 2, '2025-08-21 21:15:00', '2025-08-21 23:15:00', '4K', 1),
(13, 1, 1, '2025-08-07 10:45:00', '2025-08-07 12:45:00', '3D', 1),
(14, 1, 1, '2025-08-04 13:00:00', '2025-08-04 15:00:00', '3D', 1),
(15, 1, 4, '2025-08-24 13:00:00', '2025-08-24 15:00:00', '4K', 1),
(16, 2, 4, '2025-08-22 21:45:00', '2025-08-22 23:45:00', '3D', 3),
(17, 2, 5, '2025-08-05 10:00:00', '2025-08-05 12:00:00', '3D', 3),
(18, 2, 4, '2025-08-25 10:30:00', '2025-08-25 12:30:00', '4K', 3),
(19, 2, 5, '2025-08-16 15:45:00', '2025-08-16 17:45:00', '4K', 3),
(20, 2, 5, '2025-08-07 15:00:00', '2025-08-07 17:00:00', 'HD', 3),
(21, 3, 4, '2025-08-13 13:45:00', '2025-08-13 15:45:00', '3D', 2),
(22, 3, 5, '2025-08-22 17:30:00', '2025-08-22 19:30:00', '4K', 2),
(23, 3, 5, '2025-08-19 10:15:00', '2025-08-19 12:15:00', '4K', 2),
(24, 3, 4, '2025-08-06 13:15:00', '2025-08-06 15:15:00', '3D', 2),
(25, 3, 2, '2025-08-15 10:00:00', '2025-08-15 12:00:00', '3D', 2),
(26, 4, 1, '2025-08-22 17:15:00', '2025-08-22 19:15:00', 'HD', 1),
(27, 4, 5, '2025-08-24 19:45:00', '2025-08-24 21:45:00', 'HD', 1),
(28, 4, 5, '2025-08-11 15:30:00', '2025-08-11 17:30:00', '3D', 1),
(29, 4, 1, '2025-08-26 13:15:00', '2025-08-26 15:15:00', '3D', 1),
(30, 4, 1, '2025-08-25 13:15:00', '2025-08-25 15:15:00', 'HD', 1),
(31, 5, 2, '2025-08-25 10:15:00', '2025-08-25 12:15:00', '3D', 1),
(32, 5, 1, '2025-08-28 10:30:00', '2025-08-28 12:30:00', '3D', 1),
(33, 5, 3, '2025-08-05 15:30:00', '2025-08-05 17:30:00', '3D', 1),
(34, 5, 2, '2025-08-23 13:00:00', '2025-08-23 15:00:00', 'HD', 1),
(35, 5, 2, '2025-07-30 10:00:00', '2025-07-30 12:00:00', '4K', 1),
(36, 6, 3, '2025-08-08 19:30:00', '2025-08-08 21:30:00', '3D', 3),
(37, 6, 5, '2025-08-03 17:00:00', '2025-08-03 19:00:00', '3D', 3),
(38, 6, 4, '2025-08-06 19:15:00', '2025-08-06 21:15:00', '3D', 3),
(39, 6, 5, '2025-08-02 15:30:00', '2025-08-02 17:30:00', '3D', 3),
(40, 6, 5, '2025-08-07 19:15:00', '2025-08-07 21:15:00', 'HD', 3),
(41, 7, 1, '2025-08-04 21:15:00', '2025-08-04 23:15:00', '4K', 2),
(42, 7, 5, '2025-08-27 17:30:00', '2025-08-27 19:30:00', '4K', 2),
(43, 7, 3, '2025-08-13 19:15:00', '2025-08-13 21:15:00', '3D', 2),
(44, 7, 4, '2025-08-02 21:45:00', '2025-08-02 23:45:00', '3D', 2),
(45, 7, 4, '2025-08-14 17:45:00', '2025-08-14 19:45:00', '3D', 2),
(46, 8, 2, '2025-08-06 10:30:00', '2025-08-06 12:30:00', 'HD', 3),
(47, 8, 5, '2025-08-12 10:30:00', '2025-08-12 12:30:00', 'HD', 3),
(48, 8, 1, '2025-08-19 13:00:00', '2025-08-19 15:00:00', '4K', 3),
(49, 8, 2, '2025-08-25 15:15:00', '2025-08-25 17:15:00', '3D', 3),
(50, 8, 5, '2025-08-18 13:30:00', '2025-08-18 15:30:00', '3D', 3),
(51, 9, 3, '2025-08-04 10:00:00', '2025-08-04 12:00:00', '4K', 2),
(52, 9, 5, '2025-08-04 19:30:00', '2025-08-04 21:30:00', '4K', 2),
(53, 9, 5, '2025-08-12 13:00:00', '2025-08-12 15:00:00', '3D', 2),
(54, 9, 2, '2025-07-31 19:30:00', '2025-07-31 21:30:00', '3D', 2),
(55, 9, 4, '2025-08-22 13:45:00', '2025-08-22 15:45:00', '3D', 2),
(56, 10, 2, '2025-08-02 13:15:00', '2025-08-02 15:15:00', '4K', 2),
(57, 10, 3, '2025-08-09 13:15:00', '2025-08-09 15:15:00', '3D', 2),
(58, 10, 5, '2025-08-02 10:00:00', '2025-08-02 12:00:00', 'HD', 2),
(59, 10, 4, '2025-08-16 15:45:00', '2025-08-16 17:45:00', 'HD', 2),
(60, 10, 3, '2025-08-16 13:30:00', '2025-08-16 15:30:00', 'HD', 2);

-- --------------------------------------------------------

--
-- Structure de la table `tarifs`
--

CREATE TABLE `tarifs` (
  `qualite` varchar(10) NOT NULL,
  `prix` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tarifs`
--

INSERT INTO `tarifs` (`qualite`, `prix`) VALUES
('3D', 12.00),
('4K', 10.00),
('HD', 8.00);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `pseudo` varchar(100) DEFAULT NULL,
  `role` enum('utilisateur','employe','admin') DEFAULT 'utilisateur',
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_required` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `email`, `mot_de_passe`, `prenom`, `nom`, `pseudo`, `role`, `date_creation`, `reset_required`) VALUES
(1, 'mat.casadei@icloud.com', '$2y$10$QsElOM3g1/PwCeyR.Vc26esDwvW.3DFrr2nxR1D2oxLkAHE2o2wyC', 'Matteo', 'Casadei', 'matteo03', 'utilisateur', '2025-07-30 13:37:26', 0),
(2, 'matteo.casadei@alsace-informatique.com', '$2y$10$recrON5y5x4Y2pw7dRE5eu2coBq45kwklcy7Utj9mV7Gc1uEfpv9W', 'Mat', 'Casa', 'matteo19', 'employe', '2025-07-31 07:00:11', 1),
(3, 'matteocasadei@icloud.com', '$2y$10$r8lSkri/dPYpHRX4xD5AxOFOD2m2t7Wu1.SWHPyxvoUr/Swlzc0nm', 'Matteo', 'Cadmin', 'admin0307', 'admin', '2025-07-31 15:04:33', 0),
(4, 'mat.casadei@gmail.com', '$2y$10$zqSZotmwHDdE5S8rPs6HoOZvNItD/SSVo6BwaWWSBRobVCMcjf27u', 'Matteo', 'Cas', 'matteo0319', 'employe', '2025-08-12 07:38:38', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `film_id` (`film_id`);

--
-- Index pour la table `cinemas`
--
ALTER TABLE `cinemas`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `seance_id` (`seance_id`);

--
-- Index pour la table `films`
--
ALTER TABLE `films`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_genre` (`genre_id`);

--
-- Index pour la table `film_cinema`
--
ALTER TABLE `film_cinema`
  ADD PRIMARY KEY (`id_film`,`id_cinema`),
  ADD KEY `id_cinema` (`id_cinema`);

--
-- Index pour la table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `incidents`
--
ALTER TABLE `incidents`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `film_id` (`film_id`);

--
-- Index pour la table `places_reservees`
--
ALTER TABLE `places_reservees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`),
  ADD KEY `seance_id` (`seance_id`);

--
-- Index pour la table `salles`
--
ALTER TABLE `salles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_salle_cinema` (`cinema_id`);

--
-- Index pour la table `seances`
--
ALTER TABLE `seances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `film_id` (`film_id`);

--
-- Index pour la table `tarifs`
--
ALTER TABLE `tarifs`
  ADD PRIMARY KEY (`qualite`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `cinemas`
--
ALTER TABLE `cinemas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `films`
--
ALTER TABLE `films`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `genres`
--
ALTER TABLE `genres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `incidents`
--
ALTER TABLE `incidents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `places_reservees`
--
ALTER TABLE `places_reservees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `salles`
--
ALTER TABLE `salles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `seances`
--
ALTER TABLE `seances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`film_id`) REFERENCES `films` (`id`);

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `commandes_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `commandes_ibfk_2` FOREIGN KEY (`seance_id`) REFERENCES `seances` (`id`);

--
-- Contraintes pour la table `films`
--
ALTER TABLE `films`
  ADD CONSTRAINT `fk_genre` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `film_cinema`
--
ALTER TABLE `film_cinema`
  ADD CONSTRAINT `film_cinema_ibfk_1` FOREIGN KEY (`id_film`) REFERENCES `films` (`id`),
  ADD CONSTRAINT `film_cinema_ibfk_2` FOREIGN KEY (`id_cinema`) REFERENCES `cinemas` (`id`);

--
-- Contraintes pour la table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `notes_ibfk_2` FOREIGN KEY (`film_id`) REFERENCES `films` (`id`);

--
-- Contraintes pour la table `places_reservees`
--
ALTER TABLE `places_reservees`
  ADD CONSTRAINT `places_reservees_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`id`);

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`seance_id`) REFERENCES `seances` (`id`);

--
-- Contraintes pour la table `salles`
--
ALTER TABLE `salles`
  ADD CONSTRAINT `fk_salle_cinema` FOREIGN KEY (`cinema_id`) REFERENCES `cinemas` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `seances`
--
ALTER TABLE `seances`
  ADD CONSTRAINT `seances_ibfk_1` FOREIGN KEY (`film_id`) REFERENCES `films` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
