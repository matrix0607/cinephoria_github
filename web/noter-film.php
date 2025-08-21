
<?php
include 'config/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $film_id = $_POST['film_id'];
    $note = $_POST['note'];
    $commentaire = $_POST['commentaire'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO avis (utilisateur_id, film_id, note, commentaire) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $film_id, $note, $commentaire]);

    header("Location: espace.php");
    exit;
}
?>
