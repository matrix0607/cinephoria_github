<?php
include '../config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<p>Accès réservé aux administrateurs.</p>";
    exit;
}

// Ajout d’un film
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouter'])) {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $age = $_POST['age_minimum'];
    $coup_de_coeur = isset($_POST['coup_de_coeur']) ? 1 : 0;
    $affiche = $_POST['affiche'];
    $date_ajout = date('Y-m-d');

    $stmt = $pdo->prepare("INSERT INTO films (titre, description, age_minimum, coup_de_coeur, affiche, date_ajout)
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$titre, $description, $age, $coup_de_coeur, $affiche, $date_ajout]);
}

// Suppression
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM films WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
}

// Préparation pour modification
$film_modif = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM films WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $film_modif = $stmt->fetch();
}

// Mise à jour
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modifier'])) {
    $id = $_POST['id'];
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $age = $_POST['age_minimum'];
    $coup_de_coeur = isset($_POST['coup_de_coeur']) ? 1 : 0;
    $affiche = $_POST['affiche'];

    $stmt = $pdo->prepare("UPDATE films SET titre = ?, description = ?, age_minimum = ?, coup_de_coeur = ?, affiche = ? WHERE id = ?");
    $stmt->execute([$titre, $description, $age, $coup_de_coeur, $affiche, $id]);
}

$films = $pdo->query("SELECT * FROM films ORDER BY date_ajout DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les films - Cinéphoria (Admin)</title>
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
        .form-container input, .form-container textarea {
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
        .film-list {
            max-width: 600px;
            margin: 20px auto;
        }
        .film-item {
            background-color: #1a1a1a;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 6px;
        }
        .film-item a {
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
                Rôle : <strong><?= htmlspecialchars($_SESSION['role']) ?></strong>
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

    <h1>Gestion des films </h1>

    <div class="form-container">
        <form method="POST">
            <?php if ($film_modif): ?>
                <input type="hidden" name="id" value="<?= $film_modif['id'] ?>">
            <?php endif; ?>
            <input type="text" name="titre" placeholder="Titre" required value="<?= $film_modif['titre'] ?? '' ?>">
            <textarea name="description" placeholder="Description" required><?= $film_modif['description'] ?? '' ?></textarea>
            <input type="number" name="age_minimum" placeholder="Âge minimum" required value="<?= $film_modif['age_minimum'] ?? '' ?>">
            <label><input type="checkbox" name="coup_de_coeur" <?= isset($film_modif['coup_de_coeur']) && $film_modif['coup_de_coeur'] ? 'checked' : '' ?>> Coup de cœur</label><br><br>
            <input type="text" name="affiche" placeholder="Nom du fichier image" value="<?= $film_modif['affiche'] ?? '' ?>">
            <button type="submit" name="<?= $film_modif ? 'modifier' : 'ajouter' ?>">
                <?= $film_modif ? 'Modifier le film' : 'Ajouter le film' ?>
            </button>
        </form>
    </div>

    <div class="film-list">
        <h3>Films existants</h3>
        <?php foreach ($films as $film): ?>
            <div class="film-item">
                <strong><?= htmlspecialchars($film['titre']) ?></strong> (<?= $film['age_minimum'] ?>+)
                <a href="?edit=<?= $film['id'] ?>">✏️ Modifier</a>
                <a href="?delete=<?= $film['id'] ?>" onclick="return confirm('Supprimer ce film ?')">🗑️ Supprimer</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
