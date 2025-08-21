<?php
require_once '../includes/db.php';
require_once '../includes/header.php';
 
// Simuler un utilisateur connecté (ex : ID 1)
$utilisateur_id = 1;
 
$sql = "SELECT r.*, f.titre FROM reservations r
JOIN films f ON r.film_id = f.id
        WHERE r.utilisateur_id = :uid
        ORDER BY r.date_reservation DESC";
 
$stmt = $pdo->prepare($sql);
$stmt->execute([':uid' => $utilisateur_id]);
$reservations = $stmt->fetchAll();
 
echo "<h1>Mes réservations</h1>";
 
if (count($reservations) === 0) {
    echo "<p>Aucune réservation trouvée.</p>";
} else {
    foreach ($reservations as $res) {
        echo "<div class='reservation'>";
        echo "<h3>" . htmlspecialchars($res['titre']) . "</h3>";
        echo "<p>Date : " . $res['date'] . " à " . $res['heure'] . "</p>";
        echo "<p>Salle : " . htmlspecialchars($res['salle']) . "</p>";
        echo "<p>Réservé le : " . $res['date_reservation'] . "</p>";
        echo "</div><hr>";
    }
}
 
require_once '../includes/footer.php';
?>