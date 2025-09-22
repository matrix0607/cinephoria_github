<?php
$host = 'localhost';
$dbname = 'cinephoria';
$user = 'root';
$pass = '';

try {
    // Création du PDO avec mode exception et fetch assoc par défaut
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8", 
        $user, 
        $pass, 
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Définition de la constante BASE_URL pour tous les liens
if (!defined('BASE_URL')) {
    define('BASE_URL', '/cinephoria'); // adapte si ton dossier a un autre nom
}
?>