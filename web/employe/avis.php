
<?php
include '../config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employe') {
    echo "<p>Accès refusé.</p>";
    exit;
}

// Valider ou supprimer un avis
if (isset($_GET['valider'])) {
    $stmt = $pdo->prepare("UPDATE avis SET valide = 1 WHERE id = ?");
    $stmt->execute([$_GET['valider']]);
}
if (isset($_GET['supprimer'])) {
    $stmt = $pdo->prepare("DELETE FROM avis WHERE id = ?");
    $stmt->execute([$_GET['supprimer']]);
}

// Afficher les avis non validés
$avis = $pdo->query("SELECT a.*, u.pseudo, f.titre FROM avis a
                     JOIN utilisateurs u ON a.utilisateur_id = u.id
                     JOIN films f ON a.film_id = f.id
                     WHERE a.valide = 0")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Validation des avis - Cinéphoria</title>
    <link rel="stylesheet" href="/cinephoria/assets/css/style.css">
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to bottom, #1e2a38, #3a4a5a);
            color: #fff;
        }
        header {
            background-color: #111;
        }
        .user-info {
            text-align: right;
            padding: 10px 20px;
            font-size: 14px;
            background-color: #1a1a1a;
            line-height: 1.4;
        }
        .grid-menu {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 15px;
            max-width: 900px;
            margin: 0 auto;
            padding: 10px;
        }
        .grid-menu a {
            display: block;
            background-color: #2c3e50;
            color: #fff;
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .grid-menu a:hover {
            background-color: #3e5870;
        }
        h1 {
            text-align: center;
            margin-top: 30px;
            font-size: 32px;
        }
        .avis-container {
            max-width: 800px;
            margin: 30px auto;
            background-color: #2c3e50;
            padding: 20px;
            border-radius: 10px;
        }
        .avis {
            background-color: #1a1a1a;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 6px;
        }
        .avis p {
            margin: 5px 0;
        }
        .avis a {
            color: #66ccff;
            margin-right: 15px;
            text-decoration: none;
            font-weight: bold;
        }
        .avis a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<header>
    <?php if (isset($_SESSION['pseudo'])): ?>
        <div class="user-info">
            Connecté en tant que <strong><?= htmlspecialchars($_SESSION['pseudo']) ?></strong><br>
            Rôle : <strong>Employé</strong>
        </div>
    <?php endif; ?>
    <nav class="grid-menu">
        <a href="/cinephoria/index.php">Accueil</a>
        <a href="/cinephoria/mon_espace.php">Mon espace</a>
        <a href="/cinephoria/employe/index.php">Espace Employé</a>
        <a href="/cinephoria/logout.php">Se déconnecter</a>
        <a href="/cinephoria/reservation.php">Réservation</a>
        <a href="/cinephoria/films.php">Films</a>
        <a href="/cinephoria/contact.php">Contact</a>
    </nav>
</header>

<h1>Validation des avis</h1>

<div class="avis-container">
    <?php if (count($avis) === 0): ?>
        <p>Aucun avis à valider pour le moment.</p>
    <?php else: ?>
        <?php foreach ($avis as $a): ?>
            <div class="avis">
                <p><strong><?= htmlspecialchars($a['pseudo']) ?></strong> sur <em><?= htmlspecialchars($a['titre']) ?></em></p>
                <p>Note : <?= $a['note'] ?>/5</p>
                <p><?= nl2br(htmlspecialchars($a['commentaire'])) ?></p>
                <a href="?valider=<?= $a['id'] ?>">✅ Valider</a>
                <a href="?supprimer=<?= $a['id'] ?>" onclick="return confirm('Supprimer cet avis ?')">❌ Supprimer</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>
