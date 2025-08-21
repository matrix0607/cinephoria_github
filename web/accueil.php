<?php

require_once '../includes/db.php';



// Calcul du dernier mercredi
$today = new DateTime();
$dayOfWeek = $today->format('w'); // 0 = dimanche, 3 = mercredi
$daysSinceWednesday = ($dayOfWeek >= 3) ? $dayOfWeek - 3 : 7 - (3 - $dayOfWeek);
$lastWednesday = (clone $today)->modify("-$daysSinceWednesday days")->format('Y-m-d');

// Requête SQL pour récupérer les films ajoutés le dernier mercredi
$sql = "SELECT * FROM films WHERE DATE(date_ajout) = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$lastWednesday]);
$films = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Accueil - Cinéphoria</title>
  <link rel="stylesheet" href="../styles.css">
</head>
<body>
  <header>
    <nav>
      <div class="logo">🎥 Cinéphoria</div>
      <ul class="nav-links">
        <li><a href="#">Accueil</a></li>
        <li><a href="#">Films</a></li>
        <li><a href="#">Réservations</a></li>
        <li><a href="#">Connexion</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <section class="hero">
      <h1>Bienvenue sur Cinéphoria</h1>
      <p>Films ajoutés le mercredi <?= date('d/m/Y', strtotime($lastWednesday)) ?></p>
    </section>

    <section class="films">
      <h2>🎬 Films à l'affiche</h2>
      <div class="film-grid">
        <?php if (!empty($films)): ?>
          <?php foreach ($films as $film): ?>
            <div class="film-card">
              <img src="../affiches/<?= htmlspecialchars($film['affiche']) ?>" alt="Affiche du film">
              <h3><?= htmlspecialchars($film['titre']) ?></h3>
              <p><?= htmlspecialchars($film['genre']) ?> - <?= htmlspecialchars($film['duree']) ?> min</p>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>Aucun film ajouté ce mercredi.</p>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <footer>
    <p>📍 Cinéphoria - 123 Rue du Cinéma, 68100 Mulhouse</p>
    <p>📧 contact@cinephoria.fr | ☎️ 03 89 00 00 00</p>
    <p>&copy; 2025 Cinéphoria. Tous droits réservés.</p>
  </footer>
</body>
</html>
