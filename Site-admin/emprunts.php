<?php $pageTitle = "Accueil"; include('php-elements/header.php'); ?>

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

// Emprunts en cours (pas encore retournés)
$currentLoans = $conn->query("
    SELECT e.idEmprunt, m.titre, u.nom, u.prenom, e.dateEmprunt, e.dateRetourPrevu
    FROM emprunt e
    JOIN utilisateur u ON e.Utilisateur_idUtilisateur = u.idUtilisateur
    JOIN media m ON e.Media_idMedia = m.idMedia
    WHERE e.dateRetour IS NULL
")->fetchAll(PDO::FETCH_ASSOC);

// Historique d'emprunts (déjà retournés)
$loanHistory = $conn->query("
    SELECT e.idEmprunt, m.titre, u.nom, u.prenom, e.dateEmprunt, e.dateRetourPrevu, e.dateRetour
    FROM emprunt e
    JOIN utilisateur u ON e.Utilisateur_idUtilisateur = u.idUtilisateur
    JOIN media m ON e.Media_idMedia = m.idMedia
    WHERE e.dateRetour IS NOT NULL
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des emprunts</title>
    <style>
        html{
            scroll-behavior: smooth;
        }
        table { border-collapse: collapse; width: 100%; margin-bottom: 40px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f0f0f0; }
        .retard { color: red; font-weight: bold; }
        main{
      padding: 50px;
    }
    input#searchCurrent{
        margin-top: 20px;
        margin-bottom: 20px;
        height: 30px;
    }

    input#searchHistory{
        margin-top: 20px;
        margin-bottom: 20px;
        height: 30px;
    }

    h1{
        font-size: 30px;
    }
    .valider-btn {
        all: unset;
        padding: 5px 10px;
        background-color:rgb(0, 0, 0);
        color: white;
        text-decoration: none;
        border-radius: 3px;
        cursor: pointer;
    }
    .valider-btn:hover {
        background-color:rgb(136, 136, 136);
    }

    .move {
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
    .move:hover {
        background-color: #45a049;
    }

    </style>
</head>
<body>
    <main>

<h1 id="top">Emprunt en cours :</h1>
<input type="text" id="searchCurrent" placeholder="Rechercher un emprunt..." onkeyup="filterTable('currentLoansTable', this.value)">
<a class="move" href="#historyTable">Historique ↓</a>
<table id="currentLoansTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Date d'emprunt</th>
            <th>Date retour prévue</th>
            <th>Statut</th>
            <th>Retour</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($currentLoans as $loan): ?>
        <?php
            $retard = strtotime($loan['dateRetourPrevu']) < time();
            $statut = $retard ? '<span class="retard">En retard</span>' : 'En cours';
        ?>
        <tr>
            <td><?= $loan['idEmprunt'] ?></td>
            <td><?= htmlspecialchars($loan['titre']) ?></td>
            <td><?= htmlspecialchars($loan['nom']) ?></td>
            <td><?= htmlspecialchars($loan['prenom']) ?></td>
            <td><?= $loan['dateEmprunt'] ?></td>
            <td><?= $loan['dateRetourPrevu'] ?></td>
            <td><?= $statut ?></td>
            <td>
                <form action="valider.php" method="POST">
                    <input type="hidden" name="idEmprunt" value="<?= $loan['idEmprunt'] ?>">
                    <button class="valider-btn" type="submit">Valider retour</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<h1>Historique d'emprunts :</h1>
<input type="text" id="searchHistory" placeholder="Rechercher un emprunt..." onkeyup="filterTable('historyTable', this.value)">
<a class="move" href="#top">Emprunts en cours ↑</a>
<table id="historyTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Date d'emprunt</th>
            <th>Date retour prévue</th>
            <th>Date de retour</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($loanHistory as $loan): ?>
    <tr>
        <td><?= $loan['idEmprunt'] ?></td>
        <td><?= htmlspecialchars($loan['titre']) ?></td>
        <td><?= htmlspecialchars($loan['nom']) ?></td>
        <td><?= htmlspecialchars($loan['prenom']) ?></td>
        <td><?= $loan['dateEmprunt'] ?></td>
        <td><?= $loan['dateRetourPrevu'] ?></td>
        <td><?= $loan['dateRetour'] ?></td>
        <td>
            <form action="annuler.php" method="POST">
                <input type="hidden" name="idEmprunt" value="<?= $loan['idEmprunt'] ?>">
                <button class="valider-btn" type="submit">Annuler retour</button>
            </form>
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


