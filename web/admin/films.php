<?php
include '../config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<p>Accès réservé aux administrateurs.</p>";
    exit;
}

// --- Messages de retour utilisateur
$message = "";

// --- Ajout d’un film
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouter'])) {
    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $age = intval($_POST['age_minimum']);
    $genre_id = intval($_POST['genre_id']);
    $coup_de_coeur = isset($_POST['coup_de_coeur']) ? 1 : 0;
    $date_ajout = date('Y-m-d');

    // Gestion upload affiche
    $affiche = null;
    if (!empty($_FILES['affiche']['name'])) {
        $targetDir = __DIR__ . '/../assets/images/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

        $filename = basename($_FILES['affiche']['name']);
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];

        if (in_array($ext, $allowed)) {
            $newName = time() . "_" . preg_replace('/[^a-z0-9\._-]/i', '', $filename);
            $dest = $targetDir . $newName;
            if (move_uploaded_file($_FILES['affiche']['tmp_name'], $dest)) {
                $affiche = $newName;
            }
        }
    }

    $stmt = $pdo->prepare("INSERT INTO films (titre, description, age_minimum, coup_de_coeur, affiche, date_ajout, genre_id)
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$titre, $description, $age, $coup_de_coeur, $affiche, $date_ajout, $genre_id]);

    $message = "✅ Film ajouté avec succès.";
}

// --- Suppression
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM films WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    $message = "🗑️ Film supprimé.";
}

// --- Préparation pour modification
$film_modif = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM films WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $film_modif = $stmt->fetch();
}

// --- Mise à jour
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modifier'])) {
    $id = intval($_POST['id']);
    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);
    $age = intval($_POST['age_minimum']);
    $genre_id = intval($_POST['genre_id']);
    $coup_de_coeur = isset($_POST['coup_de_coeur']) ? 1 : 0;

    $affiche = $film_modif['affiche'] ?? null;
    if (!empty($_FILES['affiche']['name'])) {
        $targetDir = __DIR__ . '/../assets/images/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

        $filename = basename($_FILES['affiche']['name']);
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];

        if (in_array($ext, $allowed)) {
            $newName = time() . "_" . preg_replace('/[^a-z0-9\._-]/i', '', $filename);
            $dest = $targetDir . $newName;
            if (move_uploaded_file($_FILES['affiche']['tmp_name'], $dest)) {
                $affiche = $newName;
            }
        }
    }

    $stmt = $pdo->prepare("UPDATE films 
                           SET titre = ?, description = ?, age_minimum = ?, coup_de_coeur = ?, affiche = ?, genre_id = ?
                           WHERE id = ?");
    $stmt->execute([$titre, $description, $age, $coup_de_coeur, $affiche, $genre_id, $id]);

    $message = "✏️ Film modifié avec succès.";
}

// --- Liste films
$films = $pdo->query("SELECT f.*, g.nom AS genre_nom 
                      FROM films f 
                      LEFT JOIN genres g ON f.genre_id = g.id 
                      ORDER BY date_ajout DESC")->fetchAll();

// --- Récupération genres
$genres = $pdo->query("SELECT id, nom FROM genres")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les films - Cinéphoria (Admin)</title>
    <link rel="stylesheet" href="/cinephoria/assets/css/style.css">
    <style>
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background: linear-gradient(to bottom, #1e2a38, #3a4a5a); color: #fff; }
        header { background-color: #111; }
        .user-info { text-align: right; padding: 10px 20px; font-size: 14px; background-color: #1a1a1a; }
        .grid-menu { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 15px; max-width: 900px; margin: 0 auto; padding: 10px; }
        .grid-menu a { display: block; background-color: #2c3e50; color: #fff; text-align: center; padding: 15px; border-radius: 8px; text-decoration: none; font-weight: bold; transition: 0.3s; }
        .grid-menu a:hover { background-color: #3e5870; }
        h1 { text-align: center; margin-top: 30px; font-size: 32px; }
        .message { text-align: center; margin: 20px auto; padding: 10px; background: #2c3e50; border-radius: 6px; width: 50%; }
        .form-container { max-width: 600px; margin: 30px auto; background-color: #2c3e50; padding: 20px; border-radius: 10px; }
        .form-container input, .form-container textarea, .form-container select { width: 100%; margin-bottom: 10px; padding: 8px; border-radius: 5px; border: none; }
        .form-container button { padding: 10px 20px; background-color: #3e5870; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .film-list { max-width: 700px; margin: 20px auto; }
        .film-item { background-color: #1a1a1a; padding: 10px; margin-bottom: 10px; border-radius: 6px; }
        .film-item img { max-height: 60px; margin-right: 10px; vertical-align: middle; }
        .film-item a { color: #ff6666; margin-left: 10px; }
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

<h1>Gestion des films</h1>

<?php if ($message): ?>
    <div class="message"><?= $message ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="POST" enctype="multipart/form-data">
        <?php if ($film_modif): ?>
            <input type="hidden" name="id" value="<?= $film_modif['id'] ?>">
        <?php endif; ?>

        <input type="text" name="titre" placeholder="Titre" required value="<?= $film_modif['titre'] ?? '' ?>">
        <textarea name="description" placeholder="Description" required><?= $film_modif['description'] ?? '' ?></textarea>
        <input type="number" name="age_minimum" placeholder="Âge minimum" required value="<?= $film_modif['age_minimum'] ?? '' ?>">

        <label><input type="checkbox" name="coup_de_coeur" <?= !empty($film_modif['coup_de_coeur']) ? 'checked' : '' ?>> Coup de cœur</label><br><br>

        <label>Genre :</label>
        <select name="genre_id" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($genres as $g): ?>
                <option value="<?= $g['id'] ?>" <?= isset($film_modif['genre_id']) && $film_modif['genre_id'] == $g['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($g['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Affiche :</label><br>
        <input type="file" name="affiche"><br>
        <?php if (!empty($film_modif['affiche'])): ?>
            <img src="../assets/images/<?= htmlspecialchars($film_modif['affiche']) ?>" alt="" style="max-height:80px;margin-top:6px;">
        <?php endif; ?>

        <button type="submit" name="<?= $film_modif ? 'modifier' : 'ajouter' ?>">
            <?= $film_modif ? 'Modifier le film' : 'Ajouter le film' ?>
        </button>
    </form>
</div>

<div class="film-list">
    <h3>Films existants</h3>
    <?php foreach ($films as $film): ?>
        <div class="film-item">
            <?php if ($film['affiche']): ?>
                <img src="../assets/images/<?= htmlspecialchars($film['affiche']) ?>" alt="">
            <?php endif; ?>
            <strong><?= htmlspecialchars($film['titre']) ?></strong> (<?= $film['age_minimum'] ?>+) 
            — Genre : <?= htmlspecialchars($film['genre_nom'] ?? "Non défini") ?>
            <?php if ($film['coup_de_coeur']): ?> ❤️<?php endif; ?>
            <a href="?edit=<?= $film['id'] ?>">✏️ Modifier</a>
            <a href="?delete=<?= $film['id'] ?>" onclick="return confirm('Supprimer ce film ?')">🗑️ Supprimer</a>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>