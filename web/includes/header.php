
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Cinéphoria</title>
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
    </style>
</head>
<body>
    <header>
        <?php if (isset($_SESSION['pseudo'])): ?>
            <div class="user-info">
                Connecté en tant que <strong><?= htmlspecialchars($_SESSION['pseudo']) ?></strong><br>
                Rôle :
                <strong>
                    <?php
                        switch ($_SESSION['role'] ?? '') {
                            case 'admin':
                                echo 'Administrateur';
                                break;
                            case 'employe':
                                echo 'Employé';
                                break;
                            default:
                                echo 'Utilisateur';
                        }
                    ?>
                </strong>
            </div>
        <?php endif; ?>
        <nav class="grid-menu">
            <a href="/cinephoria/index.php">Accueil</a>
            <?php if (isset($_SESSION['pseudo'])): ?>
                <a href="/cinephoria/mon_espace.php">Mon espace</a>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <a href="/cinephoria/admin/index.php">Administration</a>
                <?php elseif ($_SESSION['role'] === 'employe'): ?>
                    <a href="/cinephoria/employe/index.php">Espace Employé</a>
                <?php endif; ?>
                <a href="/cinephoria/logout.php">Se déconnecter</a>
            <?php else: ?>
                <a href="/cinephoria/login.php">Se connecter</a>
            <?php endif; ?>
            <a href="/cinephoria/reservation.php">Réservation</a>
            <a href="/cinephoria/films.php">Films</a>
            <a href="/cinephoria/contact.php">Contact</a>
        </nav>
    </header>
</body>
</html>
