<?php
include '../config/db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<p>Accès refusé.</p>";
    exit;
}

// Création d’un compte employé
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO utilisateurs (email, mot_de_passe, role) VALUES (?, ?, 'employe')");
    $stmt->execute([$email, $password]);
}

// Réinitialisation
if (isset($_GET['reset'])) {
    $newPass = password_hash('1234Abcd!', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?");
    $stmt->execute([$newPass, $_GET['reset']]);
}

$employes = $pdo->query("SELECT * FROM utilisateurs WHERE role = 'employe'")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des employés - Cinéphoria</title>
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
            max-width: 500px;
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
        .employe-list {
            max-width: 600px;
            margin: 20px auto;
        }
        .employe-item {
            background-color: #1a1a1a;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 6px;
        }
        .employe-item a {
            color: #66ccff;
            margin-left: 10px;
            text-decoration: none;
        }
        .employe-item a:hover {
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

<h1>Gestion des employés</h1>

<div class="form-container">
    <form method="POST">
        <input type="email" name="email" placeholder="Email employé" required>
        <input type="password" name="password" placeholder="Mot de passe initial" required>
        <button type="submit">Créer le compte</button>
    </form>
</div>

<div class="employe-list">
    <h3>Liste des employés</h3>
    <?php foreach ($employes as $e): ?>
        <div class="employe-item">
            <?= htmlspecialchars($e['email']) ?> |
            <a href="?reset=<?= $e['id'] ?>" onclick="return confirm('Réinitialiser le mot de passe ?')">🔁 Réinitialiser mot de passe</a>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
