<?php $pageTitle = "Catégories"; include('php-elements/header.php'); ?>
<h1>Nos Catégories</h1>
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

$requete = "SELECT idType, nom FROM type";
foreach ($conn->query($requete) as $row) {
    $nom = htmlspecialchars($row['nom']);
    $idType = $row['idType'];

    echo "<a href='categories.php?idType=$idType' class='media-box'>
            <h3>$nom</h3>
          </a>";
}
?>
</main>
</body>
<?php include('php-elements/footer.php'); ?>
</html>