
<?php
include 'includes/header.php';
include 'config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo "<p>⚠️ Vous devez être connecté pour annuler une réservation.</p>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $reservation_id = $_POST['reservation_id'] ?? null;

    if (!$reservation_id) {
        echo "<p>❌ Réservation non spécifiée.</p>";
        exit;
    }

    // Supprimer les places associées
    $stmt = $pdo->prepare("DELETE FROM places_reservees WHERE reservation_id = ?");
    $stmt->execute([$reservation_id]);

    // Supprimer la réservation
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ? AND utilisateur_id = ?");
    $stmt->execute([$reservation_id, $_SESSION['user_id']]);

    echo "<p>✅ Réservation annulée avec succès.</p>";
    echo "<p><a href='mon-espace.php'>Retour à mon espace</a></p>";
} else {
    echo "<p>❌ Méthode non autorisée.</p>";
}
?>
