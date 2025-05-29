<?php
$utilisateur = "root";
$mdp = "";
$base = "djepaxhk";
$serveur = "localhost";

try {
    $conn = new PDO("mysql:host=$serveur;dbname=$base", $utilisateur, $mdp);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

$id = intval($_GET['id']);

$requete = $conn->prepare("
    SELECT Media.*, Type.nom AS type, Genre.nom AS genre
    FROM Media
    JOIN Type ON Media.type = Type.idType
    JOIN Genre ON Media.genre = Genre.idGenre
    WHERE Media.idMedia = :id
");
$requete->execute(['id' => $id]);
$media = $requete->fetch();

if (!$media) {
    die("Média introuvable.");
}

// Variables utiles
$titre = htmlspecialchars($media['titre']);
$auteur = htmlspecialchars($media['auteur']);
$editeur = htmlspecialchars($media['editeur']);
$type = htmlspecialchars($media['type']);
$genre = htmlspecialchars($media['genre']);
$disponible = $media['nombreExemplaire'] > 0 ? "Disponible" : "Indisponible";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du média</title>
</head>
<body>
    <h1><?php echo $titre; ?></h1>
    <img src="img/placeholder.png" alt="Image du média" width="200">
    <p><strong>Auteur :</strong> <?php echo $auteur; ?></p>
    <p><strong>Éditeur :</strong> <?php echo $editeur; ?></p>
    <p><strong>Type :</strong> <?php echo $type; ?></p>
    <p><strong>Genre :</strong> <?php echo $genre; ?></p>
    <p><strong>Disponibilité :</strong> <?php echo $disponible; ?></p>
    <a href="test.php">← Retour à la liste</a>
</body>
</html>
