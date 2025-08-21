
<?php
header('Content-Type: application/json');

header("Access-Control-Allow-Origin: *");

require_once '../config/db.php'; // chemin relatif corrigé

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $date_now = date('Y-m-d');

    $sql = "SELECT 
                f.titre AS film,
                f.affiche,
                s.date_heure_debut,
                s.date_heure_fin,
                sa.nom AS salle,
                r.nombre_personnes
            FROM reservations r
            JOIN seances s ON r.seance_id = s.id
            JOIN films f ON s.film_id = f.id
            JOIN salles sa ON s.salle_id = sa.id
            WHERE r.utilisateur_id = ?
            AND DATE(s.date_heure_debut) >= ?
            ORDER BY s.date_heure_debut ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $date_now]);
    $sessions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($sessions);
} else {
    echo json_encode(["error" => "Paramètre 'user_id' manquant"]);
}
?>
