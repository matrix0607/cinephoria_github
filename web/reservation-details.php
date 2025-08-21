
<?php
include 'includes/header.php';
include 'config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    echo "<p>⚠️ Vous devez être connecté pour réserver.</p>";
    exit;
}

$seance_id = $_GET['seance_id'] ?? null;
if (!$seance_id) {
    echo "<p>Séance non spécifiée.</p>";
    exit;
}

// Récupérer les infos de la séance
$stmt = $pdo->prepare("SELECT s.*, f.titre, sa.nombre_places FROM seances s
                       JOIN films f ON s.film_id = f.id
                       JOIN salles sa ON s.salle_id = sa.id
                       WHERE s.id = ?");
$stmt->execute([$seance_id]);
$seance = $stmt->fetch();

if (!$seance) {
    echo "<p>Séance introuvable.</p>";
    exit;
}

// Récupérer les places déjà réservées
$stmt = $pdo->prepare("SELECT numero_place FROM places_reservees
                       JOIN reservations r ON r.id = places_reservees.reservation_id
                       WHERE r.seance_id = ?");
$stmt->execute([$seance_id]);
$places_occupees = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $places = $_POST['places'];
    $prix_par_personne = 10.00; // à adapter selon qualité
    $prix_total = $nombre * $prix_par_personne;

    if (count($places) != $nombre) {
        echo "<p>Le nombre de sièges sélectionnés ne correspond pas au nombre de personnes.</p>";
    } else {
        $stmt = $pdo->prepare("INSERT INTO reservations (utilisateur_id, seance_id, nombre_personnes, prix_total)
                               VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $seance_id, $nombre, $prix_total]);
        $reservation_id = $pdo->lastInsertId();

        foreach ($places as $place) {
            $stmt = $pdo->prepare("INSERT INTO places_reservees (reservation_id, numero_place) VALUES (?, ?)");
            $stmt->execute([$reservation_id, $place]);
        }

        echo "<p>✅ Réservation confirmée !</p>";
        exit;
    }
}
?>

<h2>Réservation pour <?= $seance['titre'] ?></h2>
<p>Salle : <?= $seance['salle_id'] ?> | Qualité : <?= $seance['qualite'] ?></p>
<p>Places disponibles : <?= $seance['nombre_places'] - count($places_occupees) ?></p>

<form method="POST">
    <label for="nombre">Nombre de personnes :</label>
    <input type="number" name="nombre" min="1" max="10" required><br>

    <label>Choisissez vos sièges :</label><br>
    <table class="seat-table" cellspacing="5" cellpadding="5">
        <tr>
        <?php
        $seats_per_row = 6;
        for ($i = 1; $i <= $seance['nombre_places']; $i++) {
            if (($i - 1) % $seats_per_row == 0 && $i != 1) {
                echo "</tr><tr>";
            }
            $disabled = in_array($i, $places_occupees) ? 'disabled' : '';
            echo "<td><label><input type='checkbox' name='places[]' value='$i' $disabled> Siège $i</label></td>";
        }
        ?>
        </tr>
    </table>

    <button type="submit">Valider la réservation</button>
</form>

<?php include 'includes/footer.php'; ?>
