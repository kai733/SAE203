<?php
$pageTitle = "Ajouter un média";
include('php-elements/header.php');

// Connexion à la base de données
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

// Form submission
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

    // Upload image if provided
    $imagePath = null;
    if (!empty($_FILES['image']['name'])) {
        $imagePath = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
    }

    $insert = $conn->prepare("
        INSERT INTO Media (titre, auteur, editeur, dateParution, nombreExemplaire, genre, type, pageAccueil, pageNouveau, pageCoeur, description, image)
        VALUES (:titre, :auteur, :editeur, :dateParution, :nombreExemplaire, :genre, :type, :accueil, :nouveaute, :coup, :description, :img)
    ");

    $insert->execute([
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
        'img' => $imagePath
    ]);

    echo "<p style='color:green;'>Nouveau média ajouté avec succès.</p>";
}
?>
<style>
    /* same style from the edit page */
    body {
        font-family: Arial, sans-serif;
        background-color: #f9fafb;
        margin: 0;
        color: #333;
    }

    h1 {
        font-size: 30px;
        color: #2c3e50;
        margin-bottom: 20px;
    }

    form.edit {
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

    main {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 50px;
    }
</style>

<body>
<main>
    <h1>Ajouter un média</h1>

    <form class="edit" method="POST" enctype="multipart/form-data">
        <div style="display:flex; gap:20px;">
            <div>
                <img src="img/placeholder.png" alt="Image du média" width="200"><br>
                <input type="file" name="image">
            </div>

            <div>
                <label>Titre:</label>
                <input type="text" name="titre" required><br><br>

                <label>Auteur:</label>
                <input type="text" name="auteur" required><br><br>

                <label>Éditeur:</label>
                <input type="text" name="editeur"><br><br>

                <label>Date de parution (année):</label>
                <input type="number" name="dateParution" min="1000" max="2100"><br><br>

                <label>Nombre d’exemplaires:</label>
                <input type="number" name="nombreExemplaire" min="1"><br><br>

                <label>Genre:</label>
                <select name="genre">
                    <?php
                    $genres = $conn->query("SELECT idGenre, nom FROM Genre")->fetchAll();
                    foreach ($genres as $g) {
                        echo "<option value='{$g['idGenre']}'>{$g['nom']}</option>";
                    }
                    ?>
                </select><br><br>

                <label>Catégorie (type):</label><br>
                <?php
                $types = $conn->query("SELECT idType, nom FROM Type")->fetchAll();
                foreach ($types as $type) {
                    echo "<input type='radio' name='type' value='{$type['idType']}'> {$type['nom']}<br>";
                }
                ?><br>

                <label>Description:</label>
                <textarea name="description" rows="5" cols="40"></textarea><br><br>

                <label>Page d’accueil:</label>
                <input type="checkbox" name="accueil"><br>

                <label>Nos nouveautés:</label>
                <input type="checkbox" name="nouveaute"><br>

                <label>Nos coups de cœur:</label>
                <input type="checkbox" name="coup_de_coeur"><br><br>

                <input type="submit" value="Ajouter le média">
            </div>
        </div>
    </form>

    <br><a href="javascript:history.back()">← Retour à la liste</a>
</main>
<?php include('php-elements/footer.php'); ?>
</body>
</html>
