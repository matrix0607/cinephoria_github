
<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

$email = $data['email'] ?? '';

if (empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Email manquant']);
    exit;
}

$mongo = new MongoDB\Driver\Manager("mongodb+srv://Cinephoria:Matteo03@cluster0.mongodb.net/cinephoria");
$filter = ['email' => $email];
$query = new MongoDB\Driver\Query($filter);
$result = $mongo->executeQuery('cinephoria.utilisateurs', $query)->toArray();

if (count($result) === 0) {
    echo json_encode(['success' => false, 'message' => 'Email introuvable']);
    exit;
}

$newPassword = bin2hex(random_bytes(4));
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

$bulk = new MongoDB\Driver\BulkWrite;
$bulk->update(
    ['email' => $email],
    ['$set' => ['password' => $hashedPassword]]
);
$mongo->executeBulkWrite('cinephoria.utilisateurs', $bulk);

echo json_encode(['success' => true, 'message' => 'Mot de passe réinitialisé', 'new_password' => $newPassword]);
?>
