
<?php
include('php-elements/header.php');
$utilisateur = "ijtebowdelechere";
$mdp = "LucaDELECHERE2025";
$base = "ijtebowdelechere";
$serveur = "ijtebowdelechere.mysql.db";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idEmprunt'])) {
    $idEmprunt = (int) $_POST['idEmprunt'];

    try {
        $conn = new PDO("mysql:host=$serveur;dbname=$base;charset=utf8", $utilisateur, $mdp);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Set current date as return date
        $stmt = $conn->prepare("UPDATE emprunt SET dateRetour = CURDATE() WHERE idEmprunt = ?");
        $stmt->execute([$idEmprunt]);

    } catch (PDOException $e) {
        echo "<p>Erreur : " . $e->getMessage() . "</p>";
        exit();
    }

    // Redirect
    header("Location: emprunts.php");
    exit();
}
?>
<?php include('php-elements/footer.php'); ?>