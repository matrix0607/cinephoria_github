
<?php
include 'includes/header.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $nom = $_POST['nom'] ?? 'Anonyme';
    $cinema = $_POST['cinema'] ?? '';
    $sujet = $_POST['sujet'] ?? '';
    $description = $_POST['description'];

    // Simuler l'envoi d'email
    $message = "Votre message a été envoyé à Cinéphoria.<br>
                <strong>Email :</strong> $email<br>
                <strong>Nom :</strong> $nom<br>
                <strong>Cinéma :</strong> $cinema<br>
                <strong>Sujet :</strong> $sujet<br>
                <strong>Description :</strong> $description";
}
?>

<div class="contact-section">
    <h2>Contactez-nous</h2>
    <p>Une question ? Pour nous contacter, merci de bien vouloir remplir le formulaire ci-dessous.</p>

    <form method="POST" class="contact-form">
        <label for="email">E-mail *</label>
        <input type="email" name="email" id="email" required>

        <label for="nom">Nom *</label>
        <input type="text" name="nom" id="nom" required>

        <label for="cinema">Cinéma concerné</label>
        <select name="cinema" id="cinema">
            <option value="">Sélectionner un cinéma</option>
            <option value="Cinéphoria Paris">Cinéphoria Paris</option>
            <option value="Cinéphoria Lyon">Cinéphoria Strasbourg</option>
            <option value="Cinéphoria Marseille">Cinéphoria Marseille</option>
        </select>

        <label for="sujet">Sujet *</label>
        <select name="sujet" id="sujet" required>
            <option value="">Sélectionner un sujet</option>
            <option value="Réservation">Réservation</option>
            <option value="Programme">Programme</option>
            <option value="Autre">Autre</option>
        </select>

        <label for="description">Message *</label>
        <textarea name="description" id="description" required></textarea>

        <button type="submit">ENVOYER LE MESSAGE</button>
    </form>

    <p class="confirmation"><?= $message ?></p>

   
</div>

<?php include 'includes/footer.php'; ?>
