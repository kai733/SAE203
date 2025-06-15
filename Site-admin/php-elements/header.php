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
            <div class="bread-container">
              <?php include('php-elements/breadcrumb.php'); ?>
            </div>
          </div>
        </div>
      </div>
      <hr />
      <nav class="nav">
        <a href="index-admin.php">Médias</a>
        <a href="emprunts.php">Emprunts</a>
        <a href="utilisateurs.php">Utilisateurs</a>
        <a href="statistiques.php">Statistiques</a>
        <div class="search-bar">
          <form method="GET" action="index-admin.php" class="search-bar">
          <input type="text" name="search" placeholder="Chercher un média" />
          <button type="submit">
            <img src="img/nav-recherche.png" class="nav-recherche" />
          </button>
          </form>
        </div>
      </nav>
    </header>
</body>
</html>