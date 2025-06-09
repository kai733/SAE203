<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style-test.css">
    <title>Document</title>
</head>
<body>
    <div class="header-container">
        <div class="header-top">
            <img src="img/placeholder.png" alt="logo">
            <div>
                <h1>Médiathèque de la Rochefourchat</h1>
                <?php include_once 'breadcrumb.php'; generate_breadcrumb(); ?>
            </div>
        </div>
        <hr>
        <nav class="header-nav">
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="categories.php">Catégories</a></li>
                <li><a href="nouveautés.php">Nouveautés</a></li>
                <li><a href="cdc.php">Coup de Coeur</a></li>
                <li>
                    <div class="search-bar">
                        <input type="text" placeholder="Rechercher un média">
                        <button>Rechercher</button>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</body>
</html>