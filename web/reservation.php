
<?php
include 'includes/header.php';
include 'config/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isConnected = isset($_SESSION['user_id']);
$cinemas = $pdo->query("SELECT id, ville FROM cinemas")->fetchAll();
$films = $pdo->query("SELECT id, titre FROM films")->fetchAll();
?>

<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #121212;
        color: #f0f0f0;
        padding: 20px;
    }

    h2, h3 {
        text-align: center;
        color: #e50914;
    }

    form {
        max-width: 600px;
        margin: 30px auto;
        background-color: #1e1e1e;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 0 10px rgba(229, 9, 20, 0.3);
    }

    label {
        display: block;
        margin-top: 15px;
        font-weight: bold;
    }

    select {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border-radius: 8px;
        border: none;
        background-color: #2c2c2c;
        color: #fff;
    }

    button {
        margin-top: 20px;
        width: 100%;
        padding: 12px;
        background-color: #e50914;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #b00610;
    }

    .seance {
        background-color: #1e1e1e;
        margin: 20px auto;
        padding: 15px;
        border-radius: 10px;
        max-width: 600px;
        box-shadow: 0 0 8px rgba(255, 255, 255, 0.1);
    }

    .seance p {
        margin: 5px 0;
    }

    .seance a {
        display: inline-block;
        margin-top: 10px;
        color: #e50914;
        text-decoration: none;
        font-weight: bold;
    }

    .seance a:hover {
        text-decoration: underline;
    }

    .alert {
        text-align: center;
        background-color: #ffcc00;
        color: #000;
        padding: 10px;
        border-radius: 8px;
        margin: 20px auto;
        max-width: 600px;
    }
</style>

<h2>🎟️ Réserver une séance</h2>

<?php if (!$isConnected): ?>
    <div class="alert">
        ⚠️ Vous devez être connecté pour valider une réservation.<br>
        <a href="register.php">Créer un compte</a> ou <a href="login.php">Se connecter</a>
    </div>
<?php endif; ?>

<form method="GET">
    <label for="cinema">📍 Choisissez un cinéma :</label>
    <select name="cinema" id="cinema">
        <option value="">-- Sélectionner --</option>
        <?php foreach ($cinemas as $cinema): ?>
            <option value="<?= $cinema['id'] ?>" <?= ($_GET['cinema'] ?? '') == $cinema['id'] ? 'selected' : '' ?>>
                <?= $cinema['ville'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="film">🎬 Choisissez un film :</label>
    <select name="film" id="film">
        <option value="">-- Sélectionner --</option>
        <?php foreach ($films as $film): ?>
            <option value="<?= $film['id'] ?>" <?= ($_GET['film'] ?? '') == $film['id'] ? 'selected' : '' ?>>
                <?= $film['titre'] ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">🔍 Voir les séances</button>
</form>

<?php
if (!empty($_GET['film'])) {
    $filmId = $_GET['film'];
    $cinemaId = $_GET['cinema'] ?? null;

    $query = "SELECT s.*, sa.nom AS salle_nom FROM seances s
              JOIN salles sa ON s.salle_id = sa.id
              WHERE s.film_id = ?";
    $params = [$filmId];

    if (!empty($cinemaId)) {
        $query .= " AND s.cinema_id = ?";
        $params[] = $cinemaId;
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $seances = $stmt->fetchAll();

    if ($seances) {
        echo "<h3>📅 Séances disponibles :</h3>";
        foreach ($seances as $seance) {
            echo "<div class='seance'>";
            echo "<p><strong>Salle :</strong> {$seance['salle_nom']}</p>";
            echo "<p><strong>Début :</strong> {$seance['date_heure_debut']}</p>";
            echo "<p><strong>Fin :</strong> {$seance['date_heure_fin']}</p>";
            echo "<p><strong>Qualité :</strong> {$seance['qualite']}</p>";
            echo "<p><strong>Prix :</strong> à définir selon qualité et nombre de personnes</p>";
            echo "<a href='reservation-details.php?seance_id={$seance['id']}'>🎟️ Réserver cette séance</a>";
            echo "</div>";
        }
    } else {
        echo "<p style='text-align:center;'>Aucune séance disponible pour ce film dans ce cinéma.</p>";
    }
}
?>

<?php include 'includes/footer.php'; ?>
