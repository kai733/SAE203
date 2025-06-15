<?php
function generate_breadcrumb() {
    // Get the current script name, e.g. 'media.php'
    $currentFile = basename($_SERVER['PHP_SELF']); // e.g. 'media.php'
    $fileName = pathinfo($currentFile, PATHINFO_FILENAME); // e.g. 'media'

    // Optional: add query string if exists
    $queryString = $_SERVER['QUERY_STRING'] ? '?' . htmlspecialchars($_SERVER['QUERY_STRING']) : '';

    // Define readable names for certain pages
    $displayNames = [
        'index-admin' => 'Médias',
        'categorie' => 'Catégories',
        'livre' => 'Livre',
        'media' => 'Fiche média',
        'emprunts' => 'Emprunts',
        'utilisateurs' => 'Utilisateurs',
        'statistiques' => 'Statistiques',
        'ajouterMedia' => 'Ajout de média',
    ];

    echo '<nav class="breadcrumb">';

    // Always show Accueil link
    echo '<a href="index-admin.php">Accueil</a>';

    // Show the current page name if it's not Accueil
    if ($fileName !== 'index-admin') {
        $display = $displayNames[$fileName] ?? ucfirst($fileName);
        echo ' &gt; <a href="' . htmlspecialchars($currentFile . $queryString) . '">' . htmlspecialchars($display) . '</a>';
    }

    echo '</nav>';
}

// Call the function
generate_breadcrumb();
?>
