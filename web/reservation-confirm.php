<?php
include 'includes/header.php';
include 'config/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "<div class='alert'>❌ Accès interdit.</div>";
    exit;
}

$seance_id = $_POST['seance_id'];
$nombre = $_POST['nombre'];
$places = $_POST['places'] ?? [];

// Vérification séance
$stmt = $pdo->prepare("SELECT s.*, sa.nom AS salle_nom, sa.nombre_places, f.titre
                       FROM seances s
                       JOIN salles sa ON s.salle_id = sa.id
                       JOIN films f ON s.film_id = f.id
                       WHERE s.id = ? FOR UPDATE");
$stmt->execute([$seance_id]);
$seance = $stmt->fetch();

if (!$seance) {
    echo "<div class='alert'>❌ Séance introuvable.</div>";
    exit;
}

// Vérif sièges déjà pris
$stmt = $pdo->prepare("SELECT numero_place FROM places_reservees
                       JOIN reservations r ON r.id = places_reservees.reservation_id
                       WHERE r.seance_id = ?");
$stmt->execute([$seance_id]);
$places_occupees = $stmt->fetchAll(PDO::FETCH_COLUMN);

foreach ($places as $place) {
    if ($place < 1 || $place > $seance['nombre_places'] || in_array($place, $places_occupees)) {
        echo "<div class='alert'>❌ Siège $place invalide ou déjà réservé.</div>";
        exit;
    }
}

// Calcul prix
$prix_par_personne = 12.00; // ajustable
$prix_total = $nombre * $prix_par_personne;

// Enregistrement réservation
$stmt = $pdo->prepare("INSERT INTO reservations (utilisateur_id, seance_id, nombre_personnes, prix_total)
                       VALUES (?, ?, ?, ?)");
$stmt->execute([$_SESSION['user_id'], $seance_id, $nombre, $prix_total]);
$reservation_id = $pdo->lastInsertId();

foreach ($places as $place) {
    $stmt = $pdo->prepare("INSERT INTO places_reservees (reservation_id, numero_place) VALUES (?, ?)");
    $stmt->execute([$reservation_id, $place]);
}
?>

<style>
.confirm-box {
    background: #1e1e1e;
    border: 2px solid #28a745;
    color: #28a745;
    padding: 25px;
    margin: 40px auto;
    border-radius: 12px;
    text-align: center;
    width: 70%;
    box-shadow: 0 0 12px rgba(40, 167, 69, 0.5);
}
.confirm-box h2 {
    color: #28a745;
    margin-bottom: 15px;
}
.confirm-box p {
    font-size: 1.2em;
    margin: 8px 0;
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

<div class="confirm-box">
    <h2>✅ Réservation confirmée</h2>
    <p><strong>Réf:</strong> <?= $reservation_id ?></p>
    <p><strong>Film:</strong> <?= htmlspecialchars($seance['titre']) ?></p>
    <p><strong>Salle:</strong> <?= htmlspecialchars($seance['salle_nom']) ?> — 
       <strong>Sièges:</strong> <?= implode(", ", $places) ?></p>
    <p><strong>Prix total:</strong> <?= number_format($prix_total, 2) ?> €</p>
    <a href="mon_espace.php" class="btn-retour">🎟️ Voir mes réservations</a>
</div>

<?php include 'includes/footer.php'; ?>