
<?php
include 'includes/header.php';
include 'config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<p>⚠️ Vous devez être connecté pour accéder à votre espace.</p>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer les réservations de l'utilisateur
$stmt = $pdo->prepare("SELECT r.*, f.titre, s.date_heure_debut
                       FROM reservations r
                       JOIN seances s ON r.seance_id = s.id
                       JOIN films f ON s.film_id = f.id
                       WHERE r.utilisateur_id = ?
                       ORDER BY s.date_heure_debut DESC");
$stmt->execute([$user_id]);
$reservations = $stmt->fetchAll();
?>

<h2>Mon espace</h2>

<?php foreach ($reservations as $res): ?>
    <div class="reservation">
        <h3><?= $res['titre'] ?></h3>
        <p>Séance du <?= date('d/m/Y H:i', strtotime($res['date_heure_debut'])) ?></p>
        <p>Nombre de personnes : <?= $res['nombre_personnes'] ?></p>
        <p>Prix total : <?= $res['prix_total'] ?> €</p>

        <?php
        $dateSeance = new DateTime($res['date_heure_debut']);
        $now = new DateTime();

        // Si la séance est passée, proposer de noter
        if ($dateSeance < $now) {
            // Vérifier si une note existe déjà
            $stmtNote = $pdo->prepare("SELECT * FROM avis WHERE utilisateur_id = ? AND film_id = ?");
            $stmtNote->execute([$user_id, $res['seance_id']]);
            $avis = $stmtNote->fetch();

            if ($avis) {
                echo "<p>🎬 Vous avez noté ce film : {$avis['note']} / 5</p>";
                echo "<p>Commentaire : {$avis['commentaire']}</p>";
            } else {
                ?>
                <form method="POST" action="noter-film.php">
                    <input type="hidden" name="film_id" value="<?= $res['seance_id'] ?>">
                    <label for="note">Note (1 à 5) :</label>
                    <input type="number" name="note" min="1" max="5" required><br>
                    <label for="commentaire">Commentaire :</label><br>
                    <textarea name="commentaire" rows="3" cols="40"></textarea><br>
                    <button type="submit">Envoyer mon avis</button>
                </form>
                <?php
            }
        }
        ?>
    </div>
<?php endforeach; ?>

<?php include 'includes/footer.php'; ?>
