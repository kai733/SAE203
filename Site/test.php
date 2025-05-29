<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style-test.css">
</head>

<body>
    <?php
    $utilisateur = "root";
    $mdp = "";
    $base = "djepaxhk";
    $serveur = "localhost";
    $port = 3306;
    try {
        $conn = new PDO("mysql:host=$serveur;dbname=$base", $utilisateur, $mdp);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connexion OK avec PDO <br /><br />";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

    // Récupère les colonnes nécessaires
    $requete = "SELECT idMedia, titre, nombreExemplaire FROM Media";
    foreach ($conn->query($requete) as $row) {
        $titre = htmlspecialchars($row['titre']);
        $disponible = $row['nombreExemplaire'] > 0;
        $classeDisponibilite = $disponible ? "available" : "unavailable";
        $texteDisponibilite = $disponible ? "Disponible" : "Indisponible";

        echo "<a href='media.php?id={$row['idMedia']}' style='text-decoration: none; color: inherit;'>
        <div class='media-box'>
            <img src='img/placeholder.png' alt='Image du média'>
            <h3>$titre</h3>
            <p class='$classeDisponibilite'>$texteDisponibilite</p>
        </div>
      </a>";
    }
    ?>
</body>

</html>