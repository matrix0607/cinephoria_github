
<?php
include 'includes/header.php';
include 'config/db.php';

// Récupération des genres et cinémas
$cinemaStmt = $pdo->query("SELECT DISTINCT ville FROM cinemas");
$cinemas = $cinemaStmt->fetchAll();

$genreStmt = $pdo->query("SELECT id, nom FROM genres");
$genres = $genreStmt->fetchAll();

// Requête principale
$sql = "SELECT films.*, genres.nom AS genre_nom
        FROM films
        LEFT JOIN genres ON films.genre_id = genres.id";
$params = [];
$conditions = [];

if (!empty($_GET['jour'])) {
    $conditions[] = "films.id IN (
        SELECT DISTINCT film_id FROM seances WHERE DATE(date_heure_debut) = ?
    )";
    $params[] = $_GET['jour'];
}

if (!empty($_GET['cinema'])) {
    $conditions[] = "films.id IN (
        SELECT id_film FROM film_cinema 
        INNER JOIN cinemas ON film_cinema.id_cinema = cinemas.id 
        WHERE cinemas.ville = ?
    )";
    $params[] = $_GET['cinema'];
}

if (!empty($_GET['genre'])) {
    $conditions[] = "films.genre_id = ?";
    $params[] = $_GET['genre'];
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " GROUP BY films.id";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$films = $stmt->fetchAll();
?>

<div class="filtre-section">
    <h2>Films à l'affiche</h2>
    <form method="GET" class="filtres">
        <div>
            <label for="cinema">Cinéma :</label>
            <select name="cinema" id="cinema">
                <option value="">Tous les cinémas</option>
                <?php foreach ($cinemas as $cinema): ?>
                    <option value="<?= $cinema['ville'] ?>" <?= ($_GET['cinema'] ?? '') == $cinema['ville'] ? 'selected' : '' ?>>
                        <?= $cinema['ville'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="genre">Genre :</label>
            <select name="genre" id="genre">
                <option value="">Tous les genres</option>
                <?php foreach ($genres as $genre): ?>
                    <option value="<?= $genre['id'] ?>" <?= ($_GET['genre'] ?? '') == $genre['id'] ? 'selected' : '' ?>>
                        <?= $genre['nom'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="jour">Date :</label>
            <input type="date" name="jour" id="jour" value="<?= $_GET['jour'] ?? '' ?>">
        </div>

        <button type="submit">Filtrer</button>
    </form>
</div>

<div class="films">
<?php foreach ($films as $film): ?>
    <div class="film">
        <a href="film.php?id=<?= $film['id'] ?>">
            <img src="assets/images/<?= $film['affiche'] ?>" alt="<?= $film['titre'] ?>">
            <h3><?= $film['titre'] ?></h3>
            <p><?= $film['description'] ?></p>
            <p><strong>Genre :</strong> <?= $film['genre_nom'] ?? 'Non spécifié' ?></p>
            <p><strong>Âge minimum :</strong> <?= $film['age_minimum'] ?> ans</p>
            <?php if (!empty($film['coup_de_coeur'])): ?>
                <p><span class="badge">❤️ Coup de cœur</span></p>
            <?php endif; ?>
        </a>
    </div>
<?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>
