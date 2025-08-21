
<?php
include 'includes/header.php';
include 'config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer les réservations de l'utilisateur
$stmt = $pdo->prepare("
    SELECT r.id AS reservation_id, f.id AS film_id, f.titre, s.date_heure_debut, s.id AS seance_id
    FROM reservations r
    JOIN seances s ON r.seance_id = s.id
    JOIN films f ON s.film_id = f.id
    WHERE r.utilisateur_id = ?
    ORDER BY s.date_heure_debut DESC
");
$stmt->execute([$user_id]);
$reservations = $stmt->fetchAll();

echo '<div class="login-container">';
echo '<h2>Mon espace</h2>';

$now = new DateTime();

foreach ($reservations as $reservation) {
    $seanceDate = new DateTime($reservation['date_heure_debut']);
    echo "<div class='film'>";
    echo "<h3>{$reservation['titre']} - " . $seanceDate->format('d/m/Y H:i') . "</h3>";

    // Afficher les sièges réservés
    $stmtPlaces = $pdo->prepare("
        SELECT numero_place FROM places_reservees
        WHERE reservation_id = ?
    ");
    $stmtPlaces->execute([$reservation['reservation_id']]);
    $places = $stmtPlaces->fetchAll(PDO::FETCH_COLUMN);

    echo "<p>🎟️ Sièges réservés : " . implode(', ', $places) . "</p>";

    if ($seanceDate < $now) {
        // Vérifier si l'utilisateur a déjà noté ce film
        $check = $pdo->prepare("SELECT * FROM notes WHERE utilisateur_id = ? AND film_id = ?");
        $check->execute([$user_id, $reservation['film_id']]);
        if (!$check->fetch()) {
            echo '
            <form method="POST" action="noter_film.php" class="login-form">
                <input type="hidden" name="film_id" value="' . $reservation['film_id'] . '">
                <label for="note">Note (1 à 5) :</label>
                <input type="number" name="note" min="1" max="5" required>
                <label for="description">Description :</label>
                <textarea name="description" required></textarea>
                <button type="submit" class="login-btn">Envoyer</button>
            </form>';
        } else {
            echo "<p>✅ Vous avez déjà noté ce film.</p>";
        }
    } else {
        echo "<p>🎬 Séance à venir</p>";
        echo "
        <form method=\"POST\" action=\"annuler_reservation.php\" onsubmit=\"return confirm('Confirmer l\\'annulation ?');\">
            <input type=\"hidden\" name=\"reservation_id\" value=\"{$reservation['reservation_id']}\">
            <button type=\"submit\" class=\"cancel-btn\">❌ Annuler la réservation</button>
        </form>";
    }

    echo "</div>";
}

echo '</div>';
include 'includes/footer.php';
?>
