<?php
include '../config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<p>Accès réservé aux administrateurs.</p>";
    exit;
}

// Ajout d’une salle
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouter'])) {
    $nom = $_POST['nom'];
    $capacite = $_POST['capacite'];

    $stmt = $pdo->prepare("INSERT INTO salles (nom, capacite) VALUES (?, ?)");
    $stmt->execute([$nom, $capacite]);
}

// Suppression
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM salles WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
}

// Préparation pour modification
$salle_modif = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM salles WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $salle_modif = $stmt->fetch();
}

// Mise à jour
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modifier'])) {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $capacite = $_POST['capacite'];

    $stmt = $pdo->prepare("UPDATE salles SET nom = ?, capacite = ? WHERE id = ?");
    $stmt->execute([$nom, $capacite, $id]);
}

$salles = $pdo->query("SELECT * FROM salles ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les salles - Cinéphoria</title>
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
        .form-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #2c3e50;
            padding: 20px;
            border-radius: 10px;
        }
        .form-container input {
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 5px;
            border: none;
        }
        .form-container button {
            padding: 10px 20px;
            background-color: #3e5870;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .salle-list {
            max-width: 600px;
            margin: 20px auto;
        }
        .salle-item {
            background-color: #1a1a1a;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 6px;
        }
        .salle-item a {
            color: #ff6666;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <header>
        <?php if (isset($_SESSION['pseudo'])): ?>
            <div class="user-info">
                Connecté en tant que <strong><?= htmlspecialchars($_SESSION['pseudo']) ?></strong><br>
                Rôle : <strong>Administrateur</strong>
            </div>
        <?php endif; ?>
        <nav class="grid-menu">
            <a href="/cinephoria/index.php">Accueil</a>
            <a href="/cinephoria/mon_espace.php">Mon espace</a>
            <a href="/cinephoria/admin/index.php">Espace Admin</a>
            <a href="/cinephoria/logout.php">Se déconnecter</a>
            <a href="/cinephoria/reservation.php">Réservation</a>
            <a href="/cinephoria/films.php">Films</a>
            <a href="/cinephoria/contact.php">Contact</a>
        </nav>
    </header>

    <h1>Gestion des salles</h1>

    <div class="form-container">
        <form method="POST">
            <?php if ($salle_modif): ?>
                <input type="hidden" name="id" value="<?= $salle_modif['id'] ?>">
            <?php endif; ?>
            <input type="text" name="nom" placeholder="Nom de la salle" required value="<?= $salle_modif['nom'] ?? '' ?>">
            <input type="number" name="capacite" placeholder="Capacité" required value="<?= $salle_modif['capacite'] ?? '' ?>">
            <button type="submit" name="<?= $salle_modif ? 'modifier' : 'ajouter' ?>">
                <?= $salle_modif ? 'Modifier la salle' : 'Ajouter la salle' ?>
            </button>
        </form>
    </div>

    <div class="salle-list">
        <h3>Salles existantes</h3>
        <?php foreach ($salles as $salle): ?>
            <div class="salle-item">
                <strong><?= htmlspecialchars($salle['nom']) ?></strong> (<?= $salle['capacite'] ?> places)
                <a href="?edit=<?= $salle['id'] ?>">✏️ Modifier</a>
                <a href="?delete=<?= $salle['id'] ?>" onclick="return confirm('Supprimer cette salle ?')">🗑️ Supprimer</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
