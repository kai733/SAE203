<?php $pageTitle = "Média"; include('php-elements/header.php'); ?>
<?php
$utilisateur = "ijtebowdelechere";
$mdp = "LucaDELECHERE2025";
$base = "ijtebowdelechere";
$serveur = "ijtebowdelechere.mysql.db";
try {
    $conn = new PDO("mysql:host=$serveur;dbname=$base", $utilisateur, $mdp);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

$id = intval($_GET['id']);

$requete = $conn->prepare("
    SELECT media.*, type.nom AS type, genre.nom AS genre
    FROM media
    JOIN type ON media.type = type.idType
    JOIN genre ON media.genre = genre.idGenre
    WHERE media.idMedia = :id
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
$nbEx = intval($media['nombreExemplaire']);
$image = $media['image'] ?: "img/placeholder.png";

if ($nbEx > 1) {
    $disponible = "$nbEx exemplaires disponibles";
} elseif ($nbEx === 1) {
    $disponible = "1 exemplaire disponible";
} else {
    $disponible = "Pas disponible";
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9fafb;
        margin: 0;
        color: #333;
    }

    .media-container {
        max-width: 800px;
        margin: auto;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        text-align: center;
    }

    h1 {
        font-size: 28px;
        margin-bottom: 20px;
        color: #2c3e50;
    }

    img {
        max-width: 200px;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    p {
        font-size: 16px;
        margin: 10px 0;
    }

    strong {
        color: #555;
    }

    a.media{
        display: inline-block;
        margin-top: 30px;
        background-color: #3498db;
        color: white;
        padding: 10px 16px;
        border-radius: 8px;
        text-decoration: none;
        transition: background-color 0.2s ease;
    }

    a.media:hover {
        background-color: #2980b9;
    }
</style>

<body>
    <div class="media-container">
        <h1><?php echo $titre; ?></h1>
        <img src="<?php echo $image; ?>" alt="Image du média">
        <p><strong>Auteur :</strong> <?php echo $auteur; ?></p>
        <p><strong>Éditeur :</strong> <?php echo $editeur; ?></p>
        <p><strong>Type :</strong> <?php echo $type; ?></p>
        <p><strong>Genre :</strong> <?php echo $genre; ?></p>
        <p><strong>Disponibilité :</strong> <?php echo $disponible; ?></p>
        <a class="media" href="javascript:history.back()">← Retour à la liste</a>
    </div>

    <?php include('php-elements/footer.php'); ?>
</body>
</html>
