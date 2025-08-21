
<?php
include 'includes/header.php';
include 'config/db.php';



if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';

$stmt = $pdo->prepare("SELECT reset_required FROM utilisateurs WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user || $user['reset_required'] != 1) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        $message = "<div class='alert error'>Les mots de passe ne correspondent pas.</div>";
    } elseif (strlen($newPassword) < 8) {
        $message = "<div class='alert error'>Le mot de passe doit contenir au moins 8 caractères.</div>";
    } else {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ?, reset_required = 0 WHERE id = ?");
        $stmt->execute([$hashed, $_SESSION['user_id']]);

        $message = "<div class='alert success'>Votre mot de passe a été mis à jour avec succès.</div>";
    }
}
?>

<div class="reset-container">
    <h2>🔒 Réinitialisation du mot de passe</h2>
    <p>Veuillez choisir un nouveau mot de passe sécurisé.</p>

    <form method="POST" class="reset-form">
        <label for="new_password">Nouveau mot de passe</label>
        <input type="password" name="new_password" required>

        <label for="confirm_password">Confirmer le mot de passe</label>
        <input type="password" name="confirm_password" required>

        <button type="submit" class="btn">✅ Mettre à jour</button>
    </form>

    <?= $message ?>
</div>

<?php include 'includes/footer.php'; ?>
