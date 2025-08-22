
START TRANSACTION;

-- Création d'une réservation pour l'utilisateur 1 à la séance 1
INSERT INTO reservations (utilisateur_id, seance_id, nombre_personnes, prix_total)
VALUES (1, 1, 2, 20.00);

-- Réservation des sièges 5 et 6
INSERT INTO places_reservees (reservation_id, numero_place, mobilite_reduite)
VALUES (LAST_INSERT_ID(), 5, 0),
       (LAST_INSERT_ID(), 6, 0);

COMMIT;
