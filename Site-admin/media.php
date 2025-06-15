<?php
$pageTitle = "Média";
include('php-elements/header.php');

// Database connection
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

// Fetch media BEFORE processing form
$requete = $conn->prepare("
    SELECT media.*, type.nom AS typeNom, genre.nom AS genreNom
    FROM media
    JOIN type ON media.type = type.idType
    JOIN genre ON media.genre = genre.idGenre
    WHERE media.idMedia = :id
");
$requete->execute(['id' => $id]);
$media = $requete->fetch();

if (!$media) die("Média introuvable.");

// Update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $type = $_POST['type'];
    $accueil = isset($_POST['accueil']) ? 1 : 0;
    $nouveaute = isset($_POST['nouveaute']) ? 1 : 0;
    $coup_de_coeur = isset($_POST['coup_de_coeur']) ? 1 : 0;
    $auteur = $_POST['auteur'];
    $editeur = $_POST['editeur'];
    $dateParution = intval($_POST['dateParution']);
    $genre = intval($_POST['genre']);
    $nombreExemplaire = intval($_POST['nombreExemplaire']);

    // Conserver l'image existante par défaut
    $imagePath = $media['image'];

    // Si une nouvelle image est envoyée
    if (!empty($_FILES['image']['name'])) {
        $imagePath = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }

    $update = $conn->prepare("
    UPDATE media 
    SET titre = :titre, 
        auteur = :auteur,
        editeur = :editeur,
        dateParution = :dateParution,
        nombreExemplaire = :nombreExemplaire,
        genre = :genre,
        type = :type, 
        pageAccueil = :accueil, 
        pageNouveau = :nouveaute, 
        pageCoeur = :coup, 
        description = :description,
        image = :img
    WHERE idMedia = :id
");


    $update->execute([
        'titre' => $titre,
        'auteur' => $auteur,
        'editeur' => $editeur,
        'dateParution' => $dateParution,
        'nombreExemplaire' => $nombreExemplaire,
        'genre' => $genre,
        'type' => $type,
        'accueil' => $accueil,
        'nouveaute' => $nouveaute,
        'coup' => $coup_de_coeur,
        'description' => $description,
        'img' => $imagePath,
        'id' => $id
    ]);

    echo "<p style='color:green;'>Média mis à jour avec succès.</p>";
}

// Préparation pour l'affichage
$titre = htmlspecialchars($media['titre']);
$description = htmlspecialchars($media['description']);
$imgSrc = $media['image'] ?: 'img/placeholder.png';

// Fetch current loans
$empruntReq = $conn->prepare("
    SELECT e.idEmprunt, e.dateEmprunt, e.dateRetourPrevu, u.nom, u.prenom
    FROM emprunt e
    JOIN utilisateur u ON e.Utilisateur_idUtilisateur = u.idUtilisateur
    WHERE e.Media_idMedia = :id AND e.dateRetourPrevu >= CURDATE()
");
$empruntReq->execute(['id' => $id]);
$emprunts = $empruntReq->fetchAll();
?>
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f9fafb;
    margin: 0;
    color: #333;
}

h1, h2 {
    color: #2c3e50;
    margin-bottom: 20px;
}

h1{
    font-size: 30px;
}

form.edit{
    background-color: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    max-width: 1000px;
    margin-bottom: 40px;
    width: 80%;
}

form.edit div {
    margin-bottom: 15px;
}

input[type="text"],
input[type="number"],
textarea,
select {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-sizing: border-box;
    font-size: 14px;
}

input[type="file"] {
    margin-top: 10px;
}

label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
}

input[type="submit"] {
    background-color: #3498db;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

input[type="submit"]:hover {
    background-color: #2980b9;
}

input[type="checkbox"],
input[type="radio"] {
    margin-right: 6px;
}

img {
    border-radius: 8px;
    max-width: 200px;
    height: auto;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
}

table {
    border-collapse: collapse;
    width: 100%;
    background-color: #fff;
    margin-top: 10px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
}

table th, table td {
    border: 1px solid #ddd;
    padding: 12px;
    text-align: left;
}

table th {
    background-color: #f1f1f1;
}

table tr:nth-child(even) {
    background-color: #fafafa;
}

a {
    color: #3498db;
    text-decoration: none;
}

div[style*="display:flex"] {
    flex-wrap: wrap;
    align-items: flex-start;
}

@media (max-width: 768px) {
    div[style*="display:flex"] {
        flex-direction: column;
    }

    img {
        margin-bottom: 20px;
        max-width: 100%;
    }
}
main{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 50px;
}
.add-loan-btn {
    display: inline-block;
    background-color: #2ecc71;
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    margin-bottom: 20px;
    transition: background-color 0.2s ease;
}
.add-loan-btn:hover {
    background-color: #27ae60;
    text-decoration: none;
}

</style>
<body>
    <main>
    <h1>Éditer le média</h1>

    <form class="edit" method="POST" enctype="multipart/form-data">
    <div style="display:flex; gap:20px;">
    <div>
        <img src="<?php echo $imgSrc; ?>" alt="Image du média" width="200"><br>
        <input type="file" name="image">
    </div>

    <div>
        <label>Titre:</label><br>
        <input type="text" name="titre" value="<?php echo $titre; ?>" required><br><br>

        <label>Auteur:</label><br>
        <input type="text" name="auteur" value="<?php echo htmlspecialchars($media['auteur']); ?>" required><br><br>

        <label>Éditeur:</label><br>
        <input type="text" name="editeur" value="<?php echo htmlspecialchars($media['editeur']); ?>"><br><br>

        <label>Date de parution (année):</label><br>
        <input type="number" name="dateParution" value="<?php echo htmlspecialchars($media['dateParution']); ?>" min="1000" max="2100"><br><br>

        <label>Nombre d’exemplaires:</label><br>
        <input type="number" name="nombreExemplaire" value="<?php echo htmlspecialchars($media['nombreExemplaire']); ?>" min="1"><br><br>

        <label>Genre:</label><br>
        <select name="genre">
            <?php
            $genres = $conn->query("SELECT idGenre, nom FROM genre")->fetchAll();
            foreach ($genres as $g) {
                $selected = ($media['genre'] == $g['idGenre']) ? "selected" : "";
                echo "<option value='{$g['idGenre']}' $selected>{$g['nom']}</option>";
            }
            ?>
        </select><br><br>

        <label>Catégorie (type):</label><br>
        <?php
        $types = $conn->query("SELECT idType, nom FROM type")->fetchAll();
        foreach ($types as $type) {
            $checked = ($media['type'] == $type['idType']) ? "checked" : "";
            echo "<input type='radio' name='type' value='{$type['idType']}' $checked> {$type['nom']}<br>";
        }
        ?><br>

        <label>Description:</label><br>
        <textarea name="description" rows="5" cols="40"><?php echo $description; ?></textarea><br><br>

        <label>Page d’accueil:</label>
        <input type="checkbox" name="accueil" <?php if ($media['pageAccueil']) echo "checked"; ?>><br>

        <label>Nos nouveautés:</label>
        <input type="checkbox" name="nouveaute" <?php if ($media['pageNouveau']) echo "checked"; ?>><br>

        <label>Nos coups de cœur:</label>
        <input type="checkbox" name="coup_de_coeur" <?php if ($media['pageCoeur']) echo "checked"; ?>><br><br>

        <input type="submit" value="Mettre à jour">
    </div>
</div>
    </form>
    <a href="ajouterEmprunt.php?id=<?php echo $id; ?>" class="add-loan-btn">+ Ajouter un emprunt</a>
    <h2>Emprunts en cours</h2>
    <?php if ($emprunts): ?>
        <table border="1" cellpadding="5">
            <tr>
                <th>ID</th>
                <th>Produit</th>
                <th>Emprunteur</th>
                <th>Date d'emprunt</th>
                <th>Date de retour prévue</th>
            </tr>
            <?php foreach ($emprunts as $e): ?>
                <tr>
                    <td><?php echo $e['idEmprunt']; ?></td>
                    <td><?php echo $titre; ?></td>
                    <td><?php echo htmlspecialchars($e['nom']); echo htmlspecialchars($e['prenom']);?></td>
                    <td><?php echo $e['dateEmprunt']; ?></td>
                    <td><?php echo $e['dateRetourPrevu']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>Aucun emprunt en cours pour ce média.</p>
    <?php endif; ?>

    <br><a href="javascript:history.back()">← Retour à la liste</a>
    </main>
    <?php include('php-elements/footer.php'); ?>
</body>
</html>