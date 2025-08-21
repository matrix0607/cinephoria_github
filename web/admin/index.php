
<?php
include '../config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<p>Accès réservé aux administrateurs.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Administrateur - Cinéphoria</title>
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
        .admin-links {
            max-width: 800px;
            margin: 30px auto;
            background-color: #2c3e50;
            padding: 20px;
            border-radius: 10px;
        }
        .admin-links ul {
            list-style: none;
            padding: 0;
        }
        .admin-links li {
            margin: 10px 0;
        }
        .admin-links a {
            color: #66ccff;
            text-decoration: none;
            font-weight: bold;
        }
        .admin-links a:hover {
            text-decoration: underline;
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

<h1>Espace Administrateur</h1>

<div class="admin-links">
    <ul>
        <li><a href="films.php">🎬 Gérer les films</a></li>
        <li><a href="seances.php">🕒 Gérer les séances</a></li>
        <li><a href="salles.php">🏛️ Gérer les salles</a></li>
        <li><a href="employe.php">👥 Gérer les comptes employés</a></li>
        <li><a href="dashboard.php">📊 Dashboard des réservations</a></li>
    </ul>
</div>

</body>
</html>
