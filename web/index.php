Matteo0319<?php
include 'includes/header.php';
include 'config/db.php';

// Calcul du dernier mercredi
$today = new DateTime();
$dayOfWeek = $today->format('w'); // 0 = dimanche, 3 = mercredi
$daysSinceWednesday = ($dayOfWeek >= 3) ? $dayOfWeek - 3 : 7 - (3 - $dayOfWeek);
$lastWednesday = (clone $today)->modify("-$daysSinceWednesday days")->format('Y-m-d');

// Requête SQL
$stmt = $pdo->prepare("SELECT * FROM films WHERE DATE(date_ajout) = ?");
$stmt->execute([$lastWednesday]);
$films = $stmt->fetchAll();
?>

<main>
    <h2 class="titre-section">Films ajoutés le dernier mercredi</h2>
    <div class="films">
        <?php if (!empty($films)): ?>
            <?php foreach ($films as $film): ?>
                <div class="film">
                    <img src="<?= BASE_URL ?>/assets/images/<?= htmlspecialchars($film['affiche']) ?>" 
                         alt="Affiche du film <?= htmlspecialchars($film['titre']) ?>">
                    <h3><?= htmlspecialchars($film['titre']) ?></h3>
                    <p><?= htmlspecialchars($film['description']) ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun film ajouté le dernier mercredi.</p>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
