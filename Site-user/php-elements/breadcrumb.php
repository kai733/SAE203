<?php
function generate_breadcrumb() {
    $path = $_SERVER['PHP_SELF'];
    $path = str_replace("/SAE203/Site-user/", "", $path);
    $parts = explode("/", $path);

    // Tableau pour afficher des noms lisibles
    $displayNames = [
        'index-user' => 'Accueil',
        'categorie' => 'Catégories',
        'nouveaute' => 'Nouveautés',
        'coeur' => 'Coup de cœur',
        'profil' => 'Profil',
        'livre' => 'Livre',
        // Ajoutez d'autres pages ici si besoin
    ];

    echo '<nav class="breadcrumb">';
    echo '<a href="index-user.php">Accueil</a>';

    $link = '';
    foreach ($parts as $part) {
        if ($part == '' || $part == 'index.php') continue;

        $link .= $part . '/';
        $name = strtolower(str_replace('.php', '', $part));
        $display = $displayNames[$name] ?? ucfirst($name);

        echo ' &gt; ';
        echo '<a href="' . htmlspecialchars(rtrim($link, '/')) . '">' . htmlspecialchars($display) . '</a>';
    }
    echo '</nav>';
}

// Appel de la fonction
generate_breadcrumb();
?>
