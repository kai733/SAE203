<?php
// ajouter_emprunt.php
$pageTitle = "Ajouter un emprunt";
include('php-elements/header.php');

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

$idMedia = intval($_GET['id']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUtilisateur = intval($_POST['utilisateur']);
    $dateEmprunt = date('Y-m-d');
    $dateRetourPrevu = $_POST['dateRetourPrevu'];

    $stmt = $conn->prepare("INSERT INTO Emprunt (dateEmprunt, dateRetourPrevu, Utilisateur_idUtilisateur, Media_idMedia)
                            VALUES (:emprunt, :retour, :utilisateur, :media)");

    $stmt->execute([
        'emprunt' => $dateEmprunt,
        'retour' => $dateRetourPrevu,
        'utilisateur' => $idUtilisateur,
        'media' => $idMedia
    ]);

    echo "<p style='color:green;'>Emprunt ajouté avec succès.</p>";
    echo "<a href='media.php?id=$idMedia'>← Retour au média</a>";
    exit;
}

// Fetch users
$users = $conn->query("SELECT idUtilisateur, nom, prenom FROM Utilisateur ORDER BY nom")->fetchAll();
?>

<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f9fafb;
    margin: 0;
    color: #333;
}

h1 {
    color: #2c3e50;
    margin-bottom: 20px;
    font-size: 30px;
}

form.edit{
    background-color: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    max-width: 600px;
    margin-bottom: 40px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

label {
    font-weight: bold;
    display: block;
    margin-bottom: 6px;
}

input[type="date"],
select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
    box-sizing: border-box;
    margin-bottom: 15px;
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

a {
    color: #3498db;
    text-decoration: none;
    font-size: 14px;
}

a:hover {
    text-decoration: underline;
}

main{
    padding: 50px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

</style>
<main>
<h1>Ajouter un emprunt</h1>
<form class="edit" method="POST">
    <label>Utilisateur:</label><br>
    <select name="utilisateur" required>
        <option value="">-- Choisissez un utilisateur --</option>
        <?php foreach ($users as $u): ?>
            <option value="<?php echo $u['idUtilisateur']; ?>">
                <?php echo htmlspecialchars($u['nom'] . " " . $u['prenom']); ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Date de retour prévue:</label><br>
    <input type="date" name="dateRetourPrevu" required><br><br>

    <input type="submit" value="Ajouter l'emprunt">
</form>
</main>
<a href="media.php?id=<?php echo $idMedia; ?>">← Retour au média</a>

<?php include('php-elements/footer.php'); ?>
