<?php
include 'includes/header.php';
include 'config/db.php';

// Démarrage de session si nécessaire
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérification utilisateur connecté
if (!isset($_SESSION['user_id'])) {
    echo "<p>⚠️ Vous devez être connecté pour annuler une réservation.</p>";
    exit;
}

// Vérification méthode POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $reservation_id = $_POST['reservation_id'] ?? null;

    if (!$reservation_id) {
        echo "<p>❌ Réservation non spécifiée.</p>";
        exit;
    }

    try {
        // Transaction pour sécuriser la suppression
        $pdo->beginTransaction();

        // Supprimer les places associées
        $stmt = $pdo->prepare("DELETE FROM places_reservees WHERE reservation_id = ?");
        $stmt->execute([$reservation_id]);

        // Supprimer la réservation
        $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ? AND utilisateur_id = ?");
        $stmt->execute([$reservation_id, $_SESSION['user_id']]);

        $pdo->commit();

        echo "<div class='confirm-box'>";
        echo "<h2>✅ Réservation annulée avec succès</h2>";
        echo "<p><a href='" . BASE_URL . "/mon_espace.php' class='btn-retour'>Retour à mon espace</a></p>";
        echo "</div>";

    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "<p>❌ Erreur lors de l'annulation : " . htmlspecialchars($e->getMessage()) . "</p>";
    }

} else {
    echo "<p>❌ Méthode non autorisée.</p>";
}
?>

<style>
.confirm-box {
    background: #1e1e1e;
    border: 2px solid #dc3545;
    color: #dc3545;
    padding: 25px;
    margin: 40px auto;
    border-radius: 12px;
    text-align: center;
    width: 70%;
    box-shadow: 0 0 12px rgba(220, 53, 69, 0.5);
}
.confirm-box h2 {
    color: #dc3545;
    margin-bottom: 15px;
}
.btn-retour {
    display: inline-block;
    margin-top: 20px;
    padding: 12px 25px;
    background: #e50914;
    color: #fff;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    transition: 0.3s;
}
.btn-retour:hover {
    background: #b00610;
}
</style>

<?php include 'includes/footer.php'; ?>