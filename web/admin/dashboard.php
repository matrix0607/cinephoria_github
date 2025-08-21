
<?php
include '../config/db.php';
require '../vendor/autoload.php'; // MongoDB

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<p>Accès réservé aux administrateurs.</p>";
    exit;
}

// Connexion MongoDB
$mongoClient = new MongoDB\Client("mongodb+srv://Cinephoria:Matteo03@cluster0.tr4npig.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0");
$mongoCollection = $mongoClient->cinephoria->reservations;

// Récupération des données MySQL
$stmt = $pdo->query("SELECT * FROM reservations");
$mysql_reservations = $stmt->fetchAll();

// Récupération des données MongoDB
$mongo_reservations = $mongoCollection->find()->toArray();

// Fusion des données
$reservations = [];
$total_personnes = 0;
$total_prix = 0;

foreach ($mysql_reservations as $r) {
    $reservations[] = $r;
    $total_personnes += $r['nombre_personnes'];
    $total_prix += $r['prix_total'];
}

foreach ($mongo_reservations as $r) {
    $reservations[] = [
        'id' => (string)$r['_id'],
        'utilisateur_id' => $r['utilisateur_id'],
        'nombre_personnes' => $r['nombre_personnes'],
        'prix_total' => $r['prix_total'],
        'date_reservation' => $r['date_reservation']
    ];
    $total_personnes += $r['nombre_personnes'];
    $total_prix += $r['prix_total'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservations - Cinéphoria</title>
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
        }
        .grid-menu a:hover {
            background-color: #3e5870;
        }
        h1 {
            text-align: center;
            margin-top: 30px;
        }
        .table-container {
            max-width: 900px;
            margin: 30px auto;
            background-color: #2c3e50;
            padding: 20px;
            border-radius: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #1a1a1a;
            color: #fff;
        }
        th, td {
            padding: 10px;
            border: 1px solid #444;
        }
        th {
            background-color: #007BFF;
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

    <h1>Gestion des réservations</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Nombre de personnes</th>
                    <th>Prix total (€)</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['id']) ?></td>
                        <td><?= htmlspecialchars($r['utilisateur_id']) ?></td>
                        <td><?= htmlspecialchars($r['nombre_personnes']) ?></td>
                        <td><?= htmlspecialchars($r['prix_total']) ?></td>
                        <td><?= htmlspecialchars($r['date_reservation']) ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr style="font-weight: bold; background-color: #007BFF;">
                    <td colspan="2">Total</td>
                    <td><?= $total_personnes ?></td>
                    <td><?= number_format($total_prix, 2) ?></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
