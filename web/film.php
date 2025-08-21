
<?php
include 'includes/header.php';
include 'config/db.php';

if (!isset($_GET['id'])) {
    echo "<p>Film non trouvé.</p>";
    include 'includes/footer.php';
    exit;
}

$filmId = $_GET['id'];

// Récupération des tarifs
$tarifStmt = $pdo->query("SELECT * FROM tarifs");
$tarifs = [];
foreach ($tarifStmt as $row) {
    $tarifs[$row['qualite']] = $row['prix'];
}

// Récupération des infos du film
$stmt = $pdo->prepare("SELECT films.*, genres.nom AS genre_nom,
                              ROUND(AVG(notes.note), 1) AS moyenne_note,
                              COUNT(notes.id) AS nb_notes
                       FROM films
                       LEFT JOIN genres ON films.genre_id = genres.id
                       LEFT JOIN notes ON films.id = notes.film_id
                       WHERE films.id = ?
                       GROUP BY films.id");
$stmt->execute([$filmId]);
$film = $stmt->fetch();

if (!$film) {
    echo "<p>Film non trouvé.</p>";
    include 'includes/footer.php';
    exit;
}
?>

<style>
.film-detail {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    font-family: 'Segoe UI', sans-serif;
}

.film-detail h2 {
    
text-align: center;
    font-size: 3em; /* plus gros */
    margin-bottom: 20px;
    color: #fff; /* blanc */
    background-color: #222; /* fond sombre pour contraste */
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);

}

.film-detail img {
    display: block;
    margin: 0 auto 20px auto;
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}

.styled-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: #f9f9f9;
    color: #333;
}

.styled-table thead {
    background-color: #333;
    color: #fff;
}

.styled-table th, .styled-table td {
    padding: 12px 15px;
    border: 1px solid #ddd;
    text-align: center;
}

.styled-table tbody tr:nth-child(even) {
    background-color: #f2f2f2;
}

.btn-reserver {
    background-color: #007BFF;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
}

.btn-reserver:hover {
    background-color: #0056b3;
}

.badge {
    background-color: #ff4d4d;
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-weight: bold;
}
</style>

<div class="film-detail">
    <h2><?= $film['titre'] ?></h2>
    <img src="assets/images/<?= $film['affiche'] ?>" alt="<?= $film['titre'] ?>">
    <p><?= $film['description'] ?></p>
    <p><strong>Genre :</strong> <?= $film['genre_nom'] ?></p>
    <p><strong>Âge minimum :</strong> <?= $film['age_minimum'] ?> ans</p>
    <?php if (!empty($film['coup_de_coeur'])): ?>
        <p><span class="badge">❤️ Coup de cœur</span></p>
    <?php endif; ?>
    <p><strong>Note moyenne :</strong> <?= $film['moyenne_note'] ? $film['moyenne_note'] . ' / 5' : 'Pas encore de note' ?> (<?= $film['nb_notes'] ?> avis)</p>

    <h3>Séances disponibles :</h3>
    <?php
    $seanceStmt = $pdo->prepare("
        SELECT s.*, c.ville
        FROM seances s
        JOIN salles sa ON s.salle_id = sa.id
        JOIN cinemas c ON sa.cinema_id = c.id
        WHERE s.film_id = ?
        ORDER BY s.date_heure_debut
    ");
    $seanceStmt->execute([$filmId]);
    $seances = $seanceStmt->fetchAll();

    if (count($seances) > 0): ?>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>Jour</th>
                    <th>Heure</th>
                    <th>Qualité</th>
                    <th>Prix</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($seances as $seance): ?>
                    <tr>
                        <td><?= date('d/m/Y', strtotime($seance['date_heure_debut'])) ?></td>
                        <td><?= date('H:i', strtotime($seance['date_heure_debut'])) ?> - <?= date('H:i', strtotime($seance['date_heure_fin'])) ?></td>
                        <td><?= $seance['qualite'] ?></td>
                        <td><?= isset($tarifs[$seance['qualite']]) ? number_format($tarifs[$seance['qualite']], 2) . ' €' : 'Tarif inconnu' ?></td>
                        <td>
                            <form action="reservation.php" method="GET">
                                <input type="hidden" name="film_id" value="<?= $film['id'] ?>">
                                <input type="hidden" name="seance_id" value="<?= $seance['id'] ?>">
                                <input type="hidden" name="cinema" value="<?= $seance['ville'] ?>">
                                <button type="submit" class="btn-reserver">Réserver</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune séance disponible pour ce film.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
