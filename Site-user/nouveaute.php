<?php $pageTitle = "Nouveautés"; include('php-elements/header.php'); ?>
<main>
<?php
$utilisateur = "ijtebowdelechere";
$mdp = "LucaDELECHERE2025";
$base = "ijtebowdelechere";
$serveur = "ijtebowdelechere.mysql.db";

try {
    $conn = new PDO("mysql:host=$serveur;dbname=$base", $utilisateur, $mdp);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "<p>Erreur de connexion à la base de données : " . $e->getMessage() . "</p>";
    exit();
}

echo "<h2>Nouveautés</h2>";

// Sélectionner tous les médias avec "nouveau" = 1
$requete = $conn->prepare("SELECT idMedia, titre, nombreExemplaire FROM media WHERE pageNouveau = 1");
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