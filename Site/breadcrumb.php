<?php
function generate_breadcrumb() {
    $path = $_SERVER['PHP_SELF'];
    $path = str_replace("/SAE203/Site/", "", $path); // Adjust this to your folder structure
    $parts = explode("/", $path);

    echo '<nav class="breadcrumb">';
    echo '<a href="index_user.php">Accueil</a>';

    $link = '';
    foreach ($parts as $index => $part) {
        if ($part == '' || $part == 'index.php') continue;
        $link .= $part;
        echo ' &gt; ';
        echo '<a href="' . $link . '">' . ucfirst(str_replace('.php', '', $part)) . '</a>';
    }
    echo '</nav>';
}
?>