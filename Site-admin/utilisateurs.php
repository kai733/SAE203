
<?php
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

// Fetch all users
$utilisateurs = $conn->query("SELECT * FROM utilisateur")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des utilisateurs</title>
    <style>
        body { font-family: Arial, sans-serif}
        h1 { display: inline-block; }
        .ajouter-btn {
            float: right;
            margin-top: 10px;
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px;
            font-weight: 600;
        }
        .ajouter-btn:hover {
            background-color: #45a049;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .editer-btn {
            padding: 5px 10px;
            background-color:rgb(0, 0, 0);
            color: white;
            text-decoration: none;
            border-radius: 3px;
        }
        .editer-btn:hover {
            background-color:rgb(136, 136, 136);
        }
        h1{
        font-size: 30px;
        }
        main{
            padding: 50px;
        }

        input#searchCurrent{
            display: inline-block;
            width: 200px;
            height: 30px;
        }

        div.top{
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
    </style>
</head>
<body>
<main>
    <div class="top">
    <h1>Liste des utilisateurs :</h1>
    <input type="text" id="searchCurrent" placeholder="Rechercher un utilisateur..." onkeyup="filterTable('utilisateur', this.value)">
    </div>
<a class="ajouter-btn" href="ajouterUtilisateur.php">+ Ajouter un utilisateur</a>
<table id="utilisateur">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($utilisateurs as $u): ?>
            <tr>
                <td><?= $u['idUtilisateur'] ?></td>
                <td><?= htmlspecialchars($u['nom']) ?></td>
                <td><?= htmlspecialchars($u['prenom']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td>
                    <a class="editer-btn" href="editerUtilisateur.php?id=<?= $u['idUtilisateur'] ?>">Éditer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</main>
<?php include('php-elements/footer.php'); ?>
<script>
function filterTable(tableId, searchValue) {
    const input = searchValue.toLowerCase();
    const rows = document.querySelectorAll(`#${tableId} tbody tr`);
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? '' : 'none';
    });
}
</script>
</body>
</html>
