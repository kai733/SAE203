<?php include('php-elements/header.php'); ?>
<?php
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

$errors = [];
$nom = '';
$prenom = '';
$email = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Trim inputs to avoid spaces-only values
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);

    // Validate fields server-side
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
        // Insert in DB only if no errors
        $stmt = $conn->prepare("INSERT INTO utilisateur (nom, prenom, email) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $prenom, $email]);

        header("Location: utilisateurs.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un utilisateur</title>
    <style>
        /* Your CSS here (same as before) */
        html, body {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 20px;
        }
        h1 {
            font-size: 30px;
            text-align: center;
            margin-bottom: 30px;
        }
        form.edit {
            max-width: 400px;
            margin: 0 auto;
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
        input[type=text], input[type=email] {
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
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button.edit:hover {
            background-color: #45a049;
        }
        footer {
            margin-top: auto;
        }
        /* Error message style */
        .error-messages {
            max-width: 400px;
            margin: 0 auto 20px;
            padding: 10px 15px;
            background-color: #f8d7da;
            border: 1px solid #f5c2c7;
            color: #842029;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<main>
    <h1>Ajouter un nouvel utilisateur</h1>

    <?php if (!empty($errors)): ?>
        <div class="error-messages">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?php echo htmlspecialchars($err); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" class="edit" novalidate>
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" required value="<?php echo htmlspecialchars($nom); ?>">

        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" id="prenom" required value="<?php echo htmlspecialchars($prenom); ?>">

        <label for="email">Email :</label>
        <input type="email" name="email" id="email" required value="<?php echo htmlspecialchars($email); ?>">

        <button class="edit" type="submit">Ajouter l'utilisateur</button>

        <a href="utilisateurs.php">← Retour à la liste</a>
    </form>
</main>

<?php include('php-elements/footer.php'); ?>

</body>
</html>
