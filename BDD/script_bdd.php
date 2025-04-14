<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
/* --------------------------------------------------------
   Connexion à la base de données avec PHP>5.6.x (TST=100%)
   -------------------------------------------------------- */

// Pour se connecter à la BDD "mmic"
$utilisateur = "user";
$mdp = "rQUSxP2xUCxnzU45";
$base = "djepaxhk";
$serveur = "192.168.135.113";
$port = 3306;
// Initialise et retourne l'objet nécessaire pour la connexion avec PDO
try{
	$conn = new PDO("mysql:host=$serveur;dbname=$base",$utilisateur,$mdp);
	//On définit le mode d'erreur de PDO sur Exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion OK avec PDO <BR /><BR />";
    }
//On capture les exceptions si une exception est lancée et on affiche les informations relatives à celle-ci
catch(PDOException $e){
	echo "Erreur : " . $e->getMessage();
}

$requete1 = "SELECT * from Media WHERE auteur = 'The Beatles'";
$resultat1 = $conn->prepare($requete1);
$resultat1->execute();
$donnees1 = $resultat1->fetchAll(PDO::FETCH_BOTH); //Retourne la ligne suivante en tant qu'un tableau indexé par le nom et le numéro de la colonne
$nbEnreg1 = $resultat1->rowCount();

echo "Il y a ".$nbEnreg1." acteurs dans la base.<BR /><BR />";

echo "<HR size='10' noshade /><BR />";

echo "<DIV style='page-break-before: always;'>";
echo "<TABLE border=1>";
echo "<TR>";
echo "<TD bgcolor='LIGHTGREEN' align='center'>id_film</TD><TD bgcolor='LIGHTGREEN' align='center'>id_personne</TD><TD bgcolor='LIGHTGREEN' align='center'>Rôle</TD>";
echo "</TR>";

foreach ($donnees1 as $row) {
   	$id = $row[0];
	$id_personne = $row[1];
	$role= utf8_encode($row[2]);

	echo "<TR>";
	echo "<TD bgcolor='WHITE'><FONT size='4'>".$id."</FONT></TD><TD bgcolor='WHITE'><FONT size='4'>".$id_personne."</FONT></TD><TD bgcolor='WHITE'><FONT size='4'>".$role."</FONT></TD>";
	echo "</TR>";
}

//Fermeture de la connexion en PDO
$conn=null;

?>
</body>
</html>