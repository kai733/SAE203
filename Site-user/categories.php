<?php
$pageTitle = "Catégories";
include('php-elements/header.php');

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

$nomCategorie = null;

if (isset($_GET['idType'])) {
    $idType = intval($_GET['idType']); // sécurisation

    // Récupérer le nom du type
    $stmt = $conn->prepare("SELECT nom FROM type WHERE idType = ?");
    $stmt->execute([$idType]);
    $type = $stmt->fetch();

    if ($type) {
        $nomCategorie = htmlspecialchars($type['nom']);
    }
}
?>

<!-- Affichage au-dessus du main -->
<?php if ($nomCategorie): ?>
    <h2>Catégorie : <?= $nomCategorie ?></h2>
<?php elseif (isset($_GET['idType'])): ?>
    <p>Catégorie inconnue.</p>
<?php else: ?>
    <p>Paramètre idType manquant.</p>
<?php endif; ?>

<main>
<?php
if ($nomCategorie) {
    // Récupérer les médias de ce type
    $requete = $conn->prepare("SELECT idMedia, titre, nombreExemplaire FROM media WHERE type = ?");
    $requete->execute([$idType]);

    foreach ($requete as $row) {
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
}
?>
</main>
</body>
<?php include('php-elements/footer.php'); ?>
</html>