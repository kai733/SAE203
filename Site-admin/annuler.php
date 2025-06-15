<?php
$utilisateur = "ijtebowdelechere";
$mdp = "LucaDELECHERE2025";
$base = "ijtebowdelechere";
$serveur = "ijtebowdelechere.mysql.db";

try {
    $conn = new PDO("mysql:host=$serveur;dbname=$base;charset=utf8", $utilisateur, $mdp);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['idEmprunt'])) {
    $id = intval($_POST['idEmprunt']);
    $stmt = $conn->prepare("UPDATE Emprunt SET dateRetour = NULL WHERE idEmprunt = :id");
    $stmt->execute(['id' => $id]);
}

header("Location: emprunts.php"); // Replace with your loans page if needed
exit();