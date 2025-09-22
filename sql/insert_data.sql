INSERT INTO `cinemas` (`id`, `ville`) VALUES
(1, 'Paris'),
(2, 'Strasbourg'),
(3, 'Marseille');

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

INSERT INTO `genres` (`id`, `nom`) VALUES
(1, 'Action'),
(2, 'Comédie');

INSERT INTO `incidents` (`id`, `salle_id`, `description`, `date_creation`, `statut`) VALUES
(1, '1', 'siege 2', '2025-08-21 14:06:46', 'ouvert');

INSERT INTO `places_reservees` (`id`, `reservation_id`, `numero_place`, `mobilite_reduite`) VALUES
(1, 1, 2, 0),
(2, 2, 3, 0),
(3, 3, 3, 0),
(4, 4, 4, 0);

INSERT INTO `reservations` (`id`, `utilisateur_id`, `seance_id`, `nombre_personnes`, `prix_total`, `date_reservation`) VALUES
(1, 1, 1, 1, 10.00, '2025-07-30 14:59:58'),
(2, 1, 4, 1, 10.00, '2025-07-30 15:26:39'),
(3, 1, 2, 1, 10.00, '2025-08-06 09:34:03'),
(4, 1, 15, 1, 10.00, '2025-08-06 09:39:29');

INSERT INTO `salles` (`id`, `nom`, `nombre_places`, `qualite_projection`, `cinema_id`, `ville`) VALUES
(1, 'Salle Alpha', 120, '4K', 1, 'Strasbourg'),
(2, 'Salle Lumière', 100, 'HD', 1, 'Strasbourg'),
(3, 'Salle Étoile', 90, '4DX', 2, 'Paris'),
(4, 'Salle Gaumont', 150, 'IMAX', 2, 'Paris'),
(5, 'Salle Méditerranée', 110, 'HD', 3, 'Marseille'),
(6, 'Salle Provence', 80, '4K', 3, 'Marseille');

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

INSERT INTO `tarifs` (`qualite`, `prix`) VALUES
('3D', 12.00),
('4K', 10.00),
('2D', 8.00),
('4DX', 14.00),
('HD', 8.00);

INSERT INTO `utilisateurs` (`id`, `email`, `mot_de_passe`, `prenom`, `nom`, `pseudo`, `role`, `date_creation`, `reset_required`) VALUES
(1, 'mat.casadei@icloud.com', '$2y$10$QsElOM3g1/PwCeyR.Vc26esDwvW.3DFrr2nxR1D2oxLkAHE2o2wyC', 'Matteo', 'Casadei', 'matteo03', 'utilisateur', '2025-07-30 13:37:26', 0),
(2, 'matteo.casadei@alsace-informatique.com', '$2y$10$recrON5y5x4Y2pw7dRE5eu2coBq45kwklcy7Utj9mV7Gc1uEfpv9W', 'Mat', 'Casa', 'matteo19', 'employe', '2025-07-31 07:00:11', 1),
(3, 'matteocasadei@icloud.com', '$2y$10$r8lSkri/dPYpHRX4xD5AxOFOD2m2t7Wu1.SWHPyxvoUr/Swlzc0nm', 'Matteo', 'Cadmin', 'admin0307', 'admin', '2025-07-31 15:04:33', 0),
(4, 'mat.casadei@gmail.com', '$2y$10$zqSZotmwHDdE5S8rPs6HoOZvNItD/SSVo6BwaWWSBRobVCMcjf27u', 'Matteo', 'Cas', 'matteo0319', 'employe', '2025-08-12 07:38:38', 0);
