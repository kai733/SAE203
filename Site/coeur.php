<?php $pageTitle = "Coup de cœur"; include('php-elements/header.php'); ?>
<main>
<?php
$utilisateur = "root";
$mdp = "";
$base = "djepaxhk";
$serveur = "localhost";
$port = 3306;

try {
    $conn = new PDO("mysql:host=$serveur;dbname=$base", $utilisateur, $mdp);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "<p>Erreur de connexion à la base de données : " . $e->getMessage() . "</p>";
    exit();
}

echo "<h2>Coup de cœur</h2>";

// Sélectionner tous les médias avec "nouveau" = 1
$requete = $conn->prepare("SELECT idMedia, titre, nombreExemplaire FROM Media WHERE pageCoeur = 1");
$requete->execute();

$medias = $requete->fetchAll();

if (count($medias) > 0) {
    foreach ($medias as $row) {
        $titre = htmlspecialchars($row['titre']);
        $disponible = $row['nombreExemplaire'] > 0;
        $classeDisponibilite = $disponible ? "available" : "unavailable";
        $texteDisponibilite = $disponible ? "Disponible" : "Indisponible";

        echo "<div class='media-box'>
                <a href='media.php?id={$row['idMedia']}'>
                    <img src='img/placeholder.png' alt='Image du média'>
                    <h3>$titre</h3>
                    <p class='$classeDisponibilite'>$texteDisponibilite</p>
                </a>
              </div>";
    }
} else {
    echo "<p>Aucun média trouvé.</p>";
}
?>
</main>
</body>
<?php include('php-elements/footer.php'); ?>
</html>