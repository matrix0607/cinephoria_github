
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sécurité : accès réservé aux employés
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'employe') {
    header('Location: /cinephoria/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Employé - Cinéphoria</title>
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

    <h1>Espace Employé</h1>

    <div class="grid-menu" style="max-width: 600px;">
        <a href="/cinephoria/employe/films.php">Gérer les films</a>
        <a href="/cinephoria/employe/seances.php">Gérer les séances</a>
        <a href="/cinephoria/employe/salles.php">Gérer les salles</a>
        <a href="/cinephoria/employe/avis.php">Valider les avis</a>
    </div>
</body>
</html>
