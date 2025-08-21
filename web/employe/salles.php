<?php
include '../config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employe') {
    echo "<p>Accès refusé.</p>";
    exit;
}

// Ajout ou modification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $places = $_POST['places'];
    $qualite = $_POST['qualite'];

    if (isset($_POST['salle_id']) && !empty($_POST['salle_id'])) {
        // Modification
        $stmt = $pdo->prepare("UPDATE salles SET nom = ?, nombre_places = ?, qualite_projection = ? WHERE id = ?");
        $stmt->execute([$nom, $places, $qualite, $_POST['salle_id']]);
    } else {
        // Ajout
        $stmt = $pdo->prepare("INSERT INTO salles (nom, nombre_places, qualite_projection) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $places, $qualite]);
    }
}

// Suppression
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM salles WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
}

// Préremplissage pour modification
$salle_modif = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM salles WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $salle_modif = $stmt->fetch();
}

// Récupération des salles
$salles = $pdo->query("SELECT * FROM salles ORDER BY nom ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des salles - Cinéphoria</title>
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
        form {
            max-width: 500px;
            margin: 30px auto;
            background-color: #1a1a1a;
            padding: 20px;
            border-radius: 10px;
        }
        form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: none;
            border-radius: 5px;
        }
        form button {
            padding: 10px 20px;
            background-color: #2c3e50;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }
        form button:hover {
            background-color: #3e5870;
        }
        .salle-list {
            max-width: 600px;
            margin: 20px auto;
        }
        .salle-item {
            background-color: #2c3e50;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
        }
        .salle-item a {
            color: #fff;
            margin-left: 10px;
            text-decoration: none;
        }
        .salle-item a:hover {
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

    <h1>Gestion des salles</h1>

    <form method="POST">
        <input type="hidden" name="salle_id" value="<?= $salle_modif['id'] ?? '' ?>">
        <input type="text" name="nom" placeholder="Nom de la salle" required value="<?= $salle_modif['nom'] ?? '' ?>">
        <input type="number" name="places" placeholder="Nombre de places" required value="<?= $salle_modif['nombre_places'] ?? '' ?>">
        <input type="text" name="qualite" placeholder="Qualité de projection" required value="<?= $salle_modif['qualite_projection'] ?? '' ?>">
        <button type="submit"><?= $salle_modif ? 'Modifier la salle' : 'Ajouter la salle' ?></button>
    </form>

    <div class="salle-list">
        <?php foreach ($salles as $salle): ?>
            <div class="salle-item">
                <strong><?= htmlspecialchars($salle['nom']) ?></strong><br>
                Places : <?= $salle['nombre_places'] ?> | Qualité : <?= htmlspecialchars($salle['qualite_projection']) ?>
                <a href="?edit=<?= $salle['id'] ?>">✏️ Modifier</a>
                <a href="?delete=<?= $salle['id'] ?>" onclick="return confirm('Supprimer cette salle ?')">🗑️ Supprimer</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
