
<html>
  <body>
  <?php $pageTitle = "Accueil"; include('php-elements/header.php'); ?>
  <main>
  <style>
    /* Header row with title and button */

    h1{
      font-size: 30px;
    }

    .media-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .media-header h1 {
      margin: 0;
    }

    .media-header a {
      padding: 10px 16px;
      background-color: #4CAF50;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
    }

    .media-header a:hover {
      background-color: #45a049;
    }

    /* Grid container for media */
    .media-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }

    /* Media item box */
    .media-box {
      border-radius: 8px;
      padding: 12px;
      text-align: center;
      transition: box-shadow 0.2s ease-in-out;
      background-color: rgba(0, 0, 0, 0.1);
    }

    .media-box:hover {
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .media-box a{
      text-decoration: none;
      color: #000;
    }

    .media-box img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      border-radius: 4px;
      margin-bottom: 10px;
    }

    .media-box h3 {
      font-size: 1em;
      margin: 0 0 8px 0;
    }

    .available {
      color: green;
      font-weight: bold;
    }

    .unavailable {
      color: red;
      font-weight: bold;
    }

    main{
      padding: 50px;
    }
  </style>

  <?php
  // Connexion à la base de données
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

  // Recherche
  $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

  // Affichage du titre + bouton
  echo "<div class='media-header'>
          <h1>Liste des médias :</h1>
          <a href='ajouterMedia.php'>+ Ajouter un média</a>
        </div>";

  // Requête SQL selon la recherche
  if ($searchTerm !== '') {
      $requete = $conn->prepare("SELECT idMedia, image AS img, titre, nombreExemplaire, FROM media WHERE titre LIKE :term OR auteur LIKE :term");
      $requete->execute(['term' => "%$searchTerm%"]);
  } else {
      $requete = $conn->query("SELECT idMedia, titre, nombreExemplaire FROM media");
  }

 // Requête SQL selon la recherche
if ($searchTerm !== '') {
  $requete = $conn->prepare("SELECT idMedia, image, titre, nombreExemplaire FROM media WHERE titre LIKE :term OR auteur LIKE :term");
  $requete->execute(['term' => "%$searchTerm%"]);
} else {
  // Select image also here
  $requete = $conn->query("SELECT idMedia, image, titre, nombreExemplaire FROM media");
}

// Affichage des résultats
if ($requete->rowCount() === 0) {
  echo "<p>Aucun résultat trouvé pour « " . htmlspecialchars($searchTerm) . " ».</p>";
} else {
  echo "<div class='media-grid'>";
  foreach ($requete as $row) {
      $titre = htmlspecialchars($row['titre']);
      $disponible = $row['nombreExemplaire'] > 0;
      $classeDisponibilite = $disponible ? "available" : "unavailable";
      $texteDisponibilite = $disponible ? "Disponible" : "Indisponible";

      // Sanitize image path for safety
      $imgPath = htmlspecialchars($row['image'] ?: 'img/placeholder.png');

      echo "<div class='media-box'>
              <a href='media.php?id={$row['idMedia']}'>
                  <img src='{$imgPath}' alt='Image du média'>
                  <h3>$titre</h3>
                  <p class='$classeDisponibilite'>$texteDisponibilite</p>
              </a>
            </div>";
  }
  echo "</div>";
}

  ?>
</main>

<?php include('php-elements/footer.php'); ?>
</body>
</html>
