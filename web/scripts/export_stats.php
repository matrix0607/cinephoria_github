
<?php
require __DIR__ . '/../vendor/autoload.php';

// Connexion MySQL
$mysqli = new mysqli("localhost", "root", "", "cinephoria");
if ($mysqli->connect_error) {
    die("Erreur MySQL : " . $mysqli->connect_error);
}

// Connexion MongoDB
$client = new MongoDB\Client("mongodb+srv://Cinephoria:Matteo03@cluster0.tr4npig.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0");
$collection = $client->cinephoria->reservations_stats;

// Date du jour
$date = date("Y-m-d");

// Requête SQL à adapter selon ta structure réelle
$sql = "    
SELECT f.id AS film_id, f.titre, COUNT(*) AS nombre_reservations
FROM reservations r
JOIN seances s ON r.seance_id = s.id
JOIN films f ON s.film_id = f.id
WHERE DATE(r.date_reservation) = CURDATE()
GROUP BY f.id

";

$result = $mysqli->query($sql);

while ($row = $result->fetch_assoc()) {
    $collection->insertOne([
        'date' => $date,
        'film_id' => (int)$row['film_id'],
        'titre' => $row['titre'],
        'nombre_reservations' => (int)$row['nombre_reservations']
    ]);
}

echo "✅ Statistiques exportées avec succès.\n";
?>
