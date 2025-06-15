<?php include('php-elements/header.php'); ?>
<?php
$utilisateur = "ijtebowdelechere";
$mdp = "LucaDELECHERE2025";
$base = "ijtebowdelechere";
$serveur = "ijtebowdelechere.mysql.db";

try {
    $conn = new PDO("mysql:host=$serveur;dbname=$base;charset=utf8", $utilisateur, $mdp);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "<p>Erreur de connexion à la base de données : " . $e->getMessage() . "</p>";
    exit();
}

$totalMedia = $conn->query("SELECT COUNT(*) FROM media")->fetchColumn();
$currentLoans = $conn->query("SELECT COUNT(*) FROM emprunt WHERE dateRetour IS NULL")->fetchColumn();
$totalUsers = $conn->query("SELECT COUNT(*) FROM utilisateur")->fetchColumn();

$typeStats = $conn->query("
    SELECT t.nom, COUNT(m.idMedia) as count
    FROM type t
    LEFT JOIN media m ON m.type = t.idType
    GROUP BY t.idType
")->fetchAll(PDO::FETCH_ASSOC);

$lateCount = $conn->query("
    SELECT COUNT(*) FROM emprunt 
    WHERE dateRetour IS NULL AND dateRetourPrevu < CURDATE()
")->fetchColumn();

$mostLoaned = $conn->query("
    SELECT t.nom, COUNT(*) AS total
    FROM emprunt e
    JOIN media m ON e.Media_idMedia = m.idMedia
    JOIN type t ON m.type = t.idType
    GROUP BY t.idType
    ORDER BY total DESC
    LIMIT 1
")->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
        }

        .page-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
        }

        h1.titre{
            text-align: center;
            font-size: 30px;
            margin-bottom: 30px;
        }

        h2{
            padding: 20px;
            font-size: 20px;
            text-align: center;
        }

        .cards-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            flex: 1 1 250px;
            min-width: 250px;
        }

        .card h3 {
            margin-top: 0;
            font-size: 1.1em;
            color: #555;
        }

        .card p {
            font-size: 1.8em;
            font-weight: bold;
            color: #2E7D32;
            margin: 10px 0 0;
        }

        .media-types {
            margin-top: 40px;
        }

        .media-type-item {
            background-color: #fff;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 1px 5px rgba(0,0,0,0.08);
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
        }

        .highlight {
            color: #d32f2f;
            font-weight: bold;
        }

        @media (max-width: 600px) {
            .cards-container {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>

<div class="page-container">
    <h1 class="titre">Statistiques</h1>

    <div class="cards-container">
        <div class="card">
            <h3>Total de médias</h3>
            <p><?= $totalMedia ?></p>
        </div>
        <div class="card">
            <h3>Emprunts en cours</h3>
            <p><?= $currentLoans ?></p>
        </div>
        <div class="card">
            <h3>Nombre d'utilisateurs</h3>
            <p><?= $totalUsers ?></p>
        </div>
        <div class="card">
            <h3>Emprunts en retard</h3>
            <p class="highlight"><?= $lateCount ?></p>
        </div>
        <div class="card">
            <h3>Type le plus emprunté</h3>
            <p><?= $mostLoaned ? htmlspecialchars($mostLoaned['nom']) . " (" . $mostLoaned['total'] . ")" : "Aucun" ?></p>
        </div>
    </div>

    <div class="media-types">
        <h2>Répartition des types de médias</h2>
        <?php foreach ($typeStats as $type): 
            $percent = $totalMedia > 0 ? round(($type['count'] / $totalMedia) * 100, 2) : 0;
        ?>
            <div class="media-type-item">
                <div><?= htmlspecialchars($type['nom']) ?></div>
                <div><?= $type['count'] ?> (<?= $percent ?>%)</div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include('php-elements/footer.php'); ?>
</body>
</html>
