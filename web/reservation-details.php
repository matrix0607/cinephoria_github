<?php
include 'includes/header.php';
include 'config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérification utilisateur connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "/login.php");
    exit;
}

$seance_id = $_GET['seance_id'] ?? null;
if (!$seance_id) {
    echo "<div class='alert'>❌ Séance non spécifiée.</div>";
    exit;
}

// Infos de la séance
$stmt = $pdo->prepare("
    SELECT s.*, f.titre, sa.nombre_places, sa.nom AS salle_nom, c.ville
    FROM seances s
    JOIN films f ON s.film_id = f.id
    JOIN salles sa ON s.salle_id = sa.id
    JOIN cinemas c ON sa.cinema_id = c.id
    WHERE s.id = ?
");
$stmt->execute([$seance_id]);
$seance = $stmt->fetch();

if (!$seance) {
    echo "<div class='alert'>❌ Séance introuvable.</div>";
    exit;
}

// Sièges déjà pris
$stmt = $pdo->prepare("
    SELECT numero_place FROM places_reservees
    JOIN reservations r ON r.id = places_reservees.reservation_id
    WHERE r.seance_id = ?
");
$stmt->execute([$seance_id]);
$places_occupees = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Places PMR (si table exists)
$places_pmr = [];
$checkPMR = $pdo->query("SHOW TABLES LIKE 'places_pmr'")->rowCount();
if ($checkPMR) {
    $pmrStmt = $pdo->prepare("SELECT numero_place FROM places_pmr WHERE salle_id = ?");
    $pmrStmt->execute([$seance['salle_id']]);
    $places_pmr = $pmrStmt->fetchAll(PDO::FETCH_COLUMN);
}
?>

<style>
body {
    font-family: 'Poppins', sans-serif;
    background-color: #121212;
    color: #f0f0f0;
    padding: 20px;
}
h2 {
    text-align: center;
    color: #e50914;
    margin-bottom: 20px;
}
.alert {
    background: #ffcc00;
    color: #000;
    padding: 15px;
    border-radius: 8px;
    text-align: center;
    margin: 20px auto;
    max-width: 600px;
}
.seat-table {
    margin: 20px auto;
    border-collapse: collapse;
}
.seat-table td {
    padding: 8px;
    text-align: center;
}
.seat-table input[type=checkbox] {
    transform: scale(1.3);
    margin-right: 5px;
}
.pmr-seat {
    background-color: #ffcc00;
    border-radius: 4px;
    padding: 4px;
}
button {
    display: block;
    margin: 20px auto;
    padding: 12px 25px;
    border: none;
    border-radius: 8px;
    background: #e50914;
    color: white;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
}
button:hover {
    background: #b00610;
}
</style>

<h2>🎬 Réserver : <?= htmlspecialchars($seance['titre']) ?></h2>
<p style="text-align:center;">
    📍 Cinéma : <strong><?= htmlspecialchars($seance['ville']) ?></strong><br>
    🏛 Salle : <strong><?= htmlspecialchars($seance['salle_nom']) ?></strong><br>
    📅 Date : <?= date('d/m/Y H:i', strtotime($seance['date_heure_debut'])) ?><br>
    🎞 Qualité : <strong><?= htmlspecialchars($seance['qualite']) ?></strong><br>
    🪑 Places disponibles : <?= $seance['nombre_places'] - count($places_occupees) ?>
</p>

<form method="POST" action="<?= BASE_URL ?>/reservation-confirm.php">
    <input type="hidden" name="seance_id" value="<?= $seance_id ?>">

    <label for="nombre">👥 Nombre de personnes :</label><br>
    <input type="number" name="nombre" min="1" max="10" required><br><br>

    <label>🪑 Choisissez vos sièges :</label>
    <table class="seat-table">
        <tr>
        <?php
        $seats_per_row = 6; // 6 sièges par ligne
        for ($i = 1; $i <= $seance['nombre_places']; $i++) {
            if (($i - 1) % $seats_per_row == 0 && $i != 1) echo "</tr><tr>";
            $is_pmr = in_array($i, $places_pmr);
            $disabled = in_array($i, $places_occupees) ? 'disabled' : ($is_pmr ? 'disabled' : '');
            $label_class = $is_pmr ? 'pmr-seat' : '';
            echo "<td><label class='$label_class'><input type='checkbox' name='places[]' value='$i' $disabled> $i</label></td>";
        }
        ?>
        </tr>
    </table>

    <button type="submit">✅ Valider la réservation</button>
</form>

<?php include 'includes/footer.php'; ?>
