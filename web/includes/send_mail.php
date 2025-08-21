<?php
// Chargement automatique via Composer
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendResetMail($toEmail, $toName, $tempPassword) {
    $mail = new PHPMailer(true);

    try {
        // Configuration SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'u3570875792@gmail.com'; // Adresse Gmail
        $mail->Password   = 'nvyi dqvf trru eujl';   // Mot de passe d'application
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Expéditeur et destinataire
        $mail->setFrom('u3570875792@gmail.com', 'Cinéphoria');
        $mail->addAddress($toEmail, $toName);

        // Contenu HTML du mail
        $mail->isHTML(true);
        $mail->Subject = 'Réinitialisation de votre mot de passe Cinéphoria';
        $mail->Body    = "
        <html>
        <body style='font-family:Segoe UI,sans-serif;'>
            <div style='background:#fff;padding:20px;border-radius:10px;max-width:500px;margin:auto;box-shadow:0 0 10px rgba(0,0,0,0.1);'>
                <h2>🔐 MOT DE PASSE OUBLIÉ ?</h2>
                <p>Bonjour <strong>$toName</strong>,</p>
                <p>Voici votre mot de passe temporaire :</p>
                <p style='font-size:18px;color:#007BFF;font-weight:bold;'>$tempPassword</p>
                
        </body>
        </html>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur PHPMailer : " . $mail->ErrorInfo);
        return false;
    }
}