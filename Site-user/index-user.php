<?php $pageTitle = "Accueil"; include('php-elements/header.php'); ?>

<main>
  <?php
$utilisateur = "ijtebowdelechere";
$mdp = "LucaDELECHERE2025";
$base = "ijtebowdelechere";
$serveur = "ijtebowdelechere.mysql.db";

  try {
      $conn = new PDO("mysql:host=$serveur;dbname=$base", $utilisateur, $mdp);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
      echo "<p>Erreur de connexion à la base de données : " . $e->getMessage() . "</p>";
      exit();
  }

  $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
  if ($searchTerm !== '') {
      $requete = $conn->prepare("SELECT idMedia, titre, nombreExemplaire FROM media WHERE titre LIKE :term OR auteur LIKE :term");
      $requete->execute(['term' => "%$searchTerm%"]);
  } else {
      $requete = $conn->query("SELECT idMedia, titre, nombreExemplaire FROM media");
  }

  if ($requete->rowCount() === 0) {
      echo "<p>Aucun résultat trouvé pour « " . htmlspecialchars($searchTerm) . " ».</p>";
  }

  foreach ($requete as $row) {
      $titre = htmlspecialchars($row['titre']);
      $disponible = $row['nombreExemplaire'] > 0;
      $classeDisponibilite = $disponible ? "available" : "unavailable";
      $texteDisponibilite = $disponible ? "Disponible" : "Indisponible";

      echo "<div class='media-box'>
                <a href='media.php?id={$row['idMedia']}'>
                    <img src='img/placeholder.png' alt='Image du média'>
                    <h3>$titre</h3>
                    <p class='$classeDisponibilite'>$texteDisponibilite</p>
                </a>
              </div>";
  }
  ?>
</main>

<?php include('php-elements/footer.php'); ?>
  </body>
</html>