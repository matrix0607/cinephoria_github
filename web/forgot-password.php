
<?php
include 'includes/header.php';
include 'config/db.php';
require 'includes/send_mail.php'; // Assure-toi que le chemin est correct

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $tempPassword = bin2hex(random_bytes(4)) . 'A!';
        $hashed = password_hash($tempPassword, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ?, reset_required = 1 WHERE email = ?");
        $stmt->execute([$hashed, $email]);

        // Envoi du mail via PHPMailer
        if (sendResetMail($email, $user['pseudo'], $tempPassword)) {
            $message = "<div class='alert success'>Un mot de passe temporaire vous a été envoyé par e-mail.</div>";
        } else {
            $message = "<div class='alert error'>Erreur lors de l'envoi de l'e-mail. Veuillez réessayer plus tard.</div>";
        }
    } else {
        $message = "<div class='alert error'>Adresse email non trouvée.</div>";
    }
}
?>

<div class="forgot-container">
    <h2>🔐 Mot de passe oublié</h2>
    <p>Entrez votre adresse e-mail pour recevoir un mot de passe temporaire.</p>

    <form method="POST" class="forgot-form">
        <label for="email">📧 Adresse e-mail</label>
        <input type="email" name="email" placeholder="exemple@domaine.com" required>
        <button type="submit" class="btn">📩 Réinitialiser mon mot de passe</button>
    </form>

    <?= $message ?>
</div>

<?php include 'includes/footer.php'; ?>
