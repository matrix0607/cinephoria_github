<?php
include 'includes/header.php';
include 'config/db.php';

// Calcul du dernier mercredi
$today = new DateTime();
$dayOfWeek = $today->format('w'); // 0 = dimanche, 3 = mercredi
$daysSinceWednesday = ($dayOfWeek >= 3) ? $dayOfWeek - 3 : 7 - (3 - $dayOfWeek);
$lastWednesday = $today->modify("-$daysSinceWednesday days")->format('Y-m-d');

// Requête SQL
$stmt = $pdo->prepare("SELECT * FROM films WHERE date_ajout = ?");
$stmt->execute([$lastWednesday]);
$films = $stmt->fetchAll();
?>

<main>
    <h2 class="titre-section">Films ajoutés le dernier mercredi</h2>
    <div class="films">
        <?php foreach ($films as $film): ?>
            <div class="film">
                <img src="assets/images/<?= $film['affiche'] ?>" alt="<?= $film['titre'] ?>">
                <h3><?= $film['titre'] ?></h3>
                <p><?= $film['description'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
