<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../config/db.php';

// Sécurité : accès réservé aux employés
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employe') {
    header('Location: /cinephoria/index.php');
    exit;
}

// Récupération des films et salles pour les formulaires
$films = $pdo->query("SELECT id, titre FROM films")->fetchAll();
$salles = $pdo->query("SELECT id, nom FROM salles")->fetchAll();

// Traitement ajout ou modification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $film_id = $_POST['film_id'];
    $salle_id = $_POST['salle_id'];
    $debut = $_POST['debut'];
    $fin = $_POST['fin'];
    $qualite = $_POST['qualite'];

    if (isset($_POST['seance_id']) && $_POST['seance_id']) {
        // Modification
        $stmt = $pdo->prepare("UPDATE seances SET film_id=?, salle_id=?, date_heure_debut=?, date_heure_fin=?, qualite=? WHERE id=?");
        $stmt->execute([$film_id, $salle_id, $debut, $fin, $qualite, $_POST['seance_id']]);
    } else {
        // Ajout
        $stmt = $pdo->prepare("INSERT INTO seances (film_id, salle_id, date_heure_debut, date_heure_fin, qualite) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$film_id, $salle_id, $debut, $fin, $qualite]);
    }
    header("Location: seances.php");
    exit;
}

// Suppression
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM seances WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: seances.php");
    exit;
}

// Pré-remplissage pour modification
$edit_seance = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM seances WHERE id = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_seance = $stmt->fetch();
}

// Filtres
$where = [];
$params = [];

if (!empty($_GET['film_filter'])) {
    $where[] = "s.film_id = ?";
    $params[] = $_GET['film_filter'];
}
if (!empty($_GET['salle_filter'])) {
    $where[] = "s.salle_id = ?";
    $params[] = $_GET['salle_filter'];
}

$sql = "SELECT s.id, f.titre AS film, sa.nom AS salle, s.date_heure_debut, s.date_heure_fin, s.qualite
        FROM seances s
        JOIN films f ON s.film_id = f.id
        JOIN salles sa ON s.salle_id = sa.id";

if ($where) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY s.date_heure_debut DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$seances = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des séances - Cinéphoria</title>
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
        }
        form {
            max-width: 600px;
            margin: 20px auto;
            background-color: #2c3e50;
            padding: 20px;
            border-radius: 10px;
        }
        form input, form select {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
        }
        .seance-list {
            max-width: 800px;
            margin: 30px auto;
        }
        .seance-item {
            background-color: #1a1a1a;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 6px;
        }
        .seance-item a {
            color: #ff8080;
            margin-left: 10px;
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

    <h1>Gestion des séances</h1>

    <form method="POST">
        <input type="hidden" name="seance_id" value="<?= $edit_seance['id'] ?? '' ?>">
        <label>Film :</label>
        <select name="film_id" required>
            <?php foreach ($films as $f): ?>
                <option value="<?= $f['id'] ?>" <?= (isset($edit_seance['film_id']) && $edit_seance['film_id'] == $f['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($f['titre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Salle :</label>
        <select name="salle_id" required>
            <?php foreach ($salles as $s): ?>
                <option value="<?= $s['id'] ?>" <?= (isset($edit_seance['salle_id']) && $edit_seance['salle_id'] == $s['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Début :</label>
        <input type="datetime-local" name="debut" value="<?= isset($edit_seance['date_heure_debut']) ? date('Y-m-d\TH:i', strtotime($edit_seance['date_heure_debut'])) : '' ?>" required>

        <label>Fin :</label>
        <input type="datetime-local" name="fin" value="<?= isset($edit_seance['date_heure_fin']) ? date('Y-m-d\TH:i', strtotime($edit_seance['date_heure_fin'])) : '' ?>" required>

        <label>Qualité :</label>
        <input type="text" name="qualite" value="<?= $edit_seance['qualite'] ?? '' ?>" required>

        <button type="submit"><?= $edit_seance ? 'Modifier' : 'Ajouter' ?> la séance</button>
    </form>

    <form method="GET" style="max-width: 600px; margin: 20px auto; text-align: center;">
        <label for="film_filter">Filtrer par film :</label>
        <select name="film_filter" id="film_filter" onchange="this.form.submit()">
            <option value="">-- Tous les films --</option>
            <?php foreach ($films as $f): ?>
                <option value="<?= $f['id'] ?>" <?= (isset($_GET['film_filter']) && $_GET['film_filter'] == $f['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($f['titre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="salle_filter" style="margin-left: 20px;">Filtrer par salle :</label>
        <select name="salle_filter" id="salle_filter" onchange="this.form.submit()">
            <option value="">-- Toutes les salles --</option>
            <?php foreach ($salles as $s): ?>
                <option value="<?= $s['id'] ?>" <?= (isset($_GET['salle_filter']) && $_GET['salle_filter'] == $s['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <div class="seance-list">
        <?php foreach ($seances as $s): ?>
            <div class="seance-item">
                <strong><?= htmlspecialchars($s['film']) ?></strong> dans <strong><?= htmlspecialchars($s['salle']) ?></strong><br>
                Du <?= date('d/m/Y H:i', strtotime($s['date_heure_debut'])) ?> au <?= date('d/m/Y H:i', strtotime($s['date_heure_fin'])) ?> - Qualité : <?= htmlspecialchars($s['qualite']) ?>
                <a href="?edit=<?= $s['id'] ?>">✏️ Modifier</a>
                <a href="?delete=<?= $s['id'] ?>" onclick="return confirm('Supprimer cette séance ?')">🗑️ Supprimer</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
