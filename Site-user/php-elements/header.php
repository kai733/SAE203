<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo isset($pageTitle) ? $pageTitle : "Default Title"; ?></title>
    <link rel="stylesheet" href="css/reset.css" />
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body>
    <header>
      <div class="header">
        <div class="header-left">
          <img src="img/logo.png" class="logo" />
          <div class="breadcrumb">
            <h1>Médiathèque la roche fourchat</h1>
            <?php include('php-elements/breadcrumb.php'); ?>
          </div>
        </div>
      </div>
      <hr />
      <nav class="nav">
        <a href="index-user.php">Accueil</a>
        <a href="categorie.php">Catégories</a>
        <a href="nouveaute.php">Nouveautés</a>
        <a href="coeur.php">Coup de cœur</a>
        <div class="search-bar">
          <form method="GET" action="index-user.php" class="search-bar">
          <input type="text" name="search" placeholder="Que souhaitez vous rechercher ?" />
          <button type="submit">
            <img src="img/nav-recherche.png" class="nav-recherche" />
          </button>
          </form>
        </div>
      </nav>
    </header>
</body>
</html>