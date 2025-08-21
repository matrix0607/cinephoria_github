
<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
require_once '../config/db.php';

$input = json_decode(file_get_contents("php://input"), true);
$reservationId = $input['reservation_id'] ?? null;

if (!$reservationId) {
    echo json_encode(['success' => false, 'message' => 'ID réservation manquant']);
    exit;
}

$sql = "SELECT r.id, r.email, s.date_heure, f.titre, sa.nom AS salle
        FROM reservations r
        JOIN seances s ON r.seance_id = s.id
        JOIN films f ON s.film_id = f.id
        JOIN salles sa ON s.salle_id = sa.id
        WHERE r.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$reservationId]);
$details = $stmt->fetch();

if ($details) {
    echo json_encode([
        'success' => true,
        'details' => $details
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Réservation introuvable'
    ]);
}
?>
