
<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
require_once '../config/db.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$sql = "SELECT id, mot_de_passe, role FROM utilisateurs WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['mot_de_passe'])) {
    echo json_encode([
        'success' => true,
        'user_id' => $user['id'],
        'role' => $user['role']
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Identifiants incorrects'
    ]);
}
?>
