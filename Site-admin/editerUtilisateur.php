<?php 
$pageTitle = "Accueil"; 
include('php-elements/header.php'); 

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

// Validate GET id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>ID utilisateur invalide.</p>";
    exit();
}

$id = (int) $_GET['id'];

// Initialize error array and fields
$errors = [];
$nom = '';
$prenom = '';
$email = '';

// If form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);

    // Server-side validation
    if ($nom === '') {
        $errors[] = "Le nom est obligatoire.";
    }
    if ($prenom === '') {
        $errors[] = "Le prénom est obligatoire.";
    }
    if ($email === '') {
        $errors[] = "L'email est obligatoire.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide.";
    }

    if (empty($errors)) {
        // Update user in DB
        $stmt = $conn->prepare("UPDATE utilisateur SET nom = ?, prenom = ?, email = ? WHERE idUtilisateur = ?");
        $stmt->execute([$nom, $prenom, $email, $id]);

        header("Location: utilisateurs.php");
        exit();
    }
} else {
    // Load user data for first display
    $stmt = $conn->prepare("SELECT * FROM utilisateur WHERE idUtilisateur = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "<p>Utilisateur non trouvé.</p>";
        exit();
    }

    // Pre-fill fields with current DB data
    $nom = $user['nom'];
    $prenom = $user['prenom'];
    $email = $user['email'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Éditer utilisateur</title>
    <style>
        /* Full height and flex column layout */
        html, body {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        main {
            flex-grow: 1; /* Grow main content to push footer down */
            padding: 20px;
        }

        h1 {
            font-size: 30px;
            margin-top: 10px;
            text-align: center;
        }

        form.edit {
            max-width: 400px;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background-color: #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input[type=text].edit, input[type=email].edit {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        button.edit {
            margin-top: 20px;
            padding: 10px 16px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.edit:hover {
            background-color: #1976D2;
        }

        a {
            display: inline-block;
            margin-top: 10px;
            color: #2196F3;
        }

        .error-messages {
            max-width: 400px;
            margin: 20px auto 0;
            padding: 10px 15px;
            background-color: #f8d7da;
            border: 1px solid #f5c2c7;
            color: #842029;
            border-radius: 4px;
            list-style-type: disc;
            list-style-position: inside;
        }

        footer {
            margin-top: auto;
            text-align: center;
            font-size: 14px;
            border-top: 1px solid #ccc;
        }
    </style>
</head>
<body>

<main>
    <h1>Modifier un utilisateur</h1>

    <?php if (!empty($errors)): ?>
        <ul class="error-messages">
            <?php foreach ($errors as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="POST" class="edit" novalidate>
        <label for="nom">Nom :</label>
        <input class="edit" type="text" name="nom" id="nom" value="<?= htmlspecialchars($nom) ?>" required>

        <label for="prenom">Prénom :</label>
        <input class="edit" type="text" name="prenom" id="prenom" value="<?= htmlspecialchars($prenom) ?>" required>

        <label for="email">Email :</label>
        <input class="edit" type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>" required>

        <button class="edit" type="submit">Enregistrer les modifications</button><br>
        <a href="utilisateurs.php">← Retour à la liste</a>
    </form>
</main>

<?php include('php-elements/footer.php'); ?>

</body>
</html>
