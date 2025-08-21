<?php
include 'includes/header.php';
include 'config/db.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $pseudo = $_POST['pseudo'];

    // Vérification du mot de passe
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
        $message = "Mot de passe invalide.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO utilisateurs (email, mot_de_passe, prenom, nom, pseudo, role) VALUES (?, ?, ?, ?, ?, 'utilisateur')");
        if ($stmt->execute([$email, $hashedPassword, $prenom, $nom, $pseudo])) {
            $message = "Compte créé avec succès. Un email de confirmation vous a été envoyé.";
        } else {
            $message = "Erreur lors de la création du compte.";
        }
    }
}
?>

<div class="login-container">
    <h2>Créer un compte</h2>
    <div class="tabs">
        <button onclick="window.location.href='login.php'">Je m'identifie</button>
        <button class="active">Je crée un compte</button>
    </div>
    <form method="POST" class="login-form">
        <label for="email">E-mail*</label>
        <input type="email" name="email" required>

        <label for="password">Mot de passe*</label>
        <input type="password" name="password" required>

        <label for="prenom">Prénom*</label>
        <input type="text" name="prenom" required>

        <label for="nom">Nom*</label>
        <input type="text" name="nom" required>

        <label for="pseudo">Nom d'utilisateur*</label>
        <input type="text" name="pseudo" required>

        <button type="submit" class="login-btn">Créer mon compte</button>
        <p class="required-note">* Champs obligatoires</p>
        <p class="error"><?= $message ?></p>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
