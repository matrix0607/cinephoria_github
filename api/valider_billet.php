
<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['reservation_id'])) {
    echo json_encode(['success' => false, 'message' => 'ID de réservation manquant']);
    exit;
}

$reservation_id = intval($data['reservation_id']);

// Connexion à MongoDB ou MySQL selon ton système
// Exemple : mise à jour du champ "valide" à true
// $db->update(['_id' => $reservation_id], ['$set' => ['valide' => true]]);

echo json_encode(['success' => true, 'message' => 'Billet validé avec succès']);
?>
