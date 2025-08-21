
<?php
include 'includes/header.php';
include 'config/db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['pseudo'] = $user['pseudo'];

        if (!empty($user['reset_required']) && $user['reset_required'] == 1) {
            header("Location: reset_password.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $message = "Identifiants incorrects.";
    }
}
?>

<div class="login-container">
    <h2>Connexion</h2>
    <div class="tabs">
        <button class="active">Je m'identifie</button>
        <button onclick="window.location.href='register.php'">Je crée un compte</button>
    </div>
    <form method="POST" class="login-form">
        <label for="email">E-mail*</label>
        <input type="email" name="email" required>

        <label for="password">Mot de passe*</label>
        <input type="password" name="password" required>

        <div class="remember">
            <input type="checkbox" name="remember" id="remember">
            <label for="remember">Se souvenir de moi</label>
        </div>

        <button type="submit" class="login-btn">Je me connecte</button>
        <p class="required-note">* Champs obligatoires</p>
        <p class="error"><?= $message ?></p>

        <div class="links">
            <a href="forgot-password.php">Mot de passe oublié ?</a> |
            <a href="#">Renvoyer le mail de validation du compte</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
