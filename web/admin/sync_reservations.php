
<?php
session_start();
include(__DIR__ . '/../config/db.php');

if (!isset($pdo)) {
    die("Erreur : connexion à la base de données non établie.");
}

try {
    $stmt = $pdo->query("SELECT * FROM reservations");
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des réservations : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des réservations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 40px;
        }
        h2 {
            color: #333;
        }
        .reservation-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .reservation-table th, .reservation-table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .reservation-table th {
            background-color: #007BFF;
            color: white;
        }
        .reservation-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<h2>Liste des réservations</h2>

<?php if ($reservations): ?>
    <table class="reservation-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Utilisateur</th>
                <th>Nombre de personnes</th>
                <th>Prix total (€)</th>
                <th>Date de réservation</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['utilisateur_id']) ?></td>
                    <td><?= htmlspecialchars($row['nombre_personnes']) ?></td>
                    <td><?= htmlspecialchars($row['prix_total']) ?></td>
                    <td><?= htmlspecialchars($row['date_reservation']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucune réservation trouvée.</p>
<?php endif; ?>

</body>
</html>
