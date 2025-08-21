
<?php
require 'vendor/autoload.php'; // Charge la bibliothèque MongoDB

$uri = "mongodb+srv://Cinephoria:Matteo03@cluster0.tr4npig.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0";

$client = new MongoDB\Client($uri);

try {
    $databases = $client->listDatabases();
    echo "✅ Connexion réussie à MongoDB Atlas !<br><br>";
    foreach ($databases as $db) {
        echo "📁 " . $db->getName() . "<br>";
    }
} catch (Exception $e) {
    echo "❌ Erreur de connexion : " . $e->getMessage();
}
?>
