<?php
include_once 'header.php';

// on detruit toutes les variables dans $_SESSION
session_unset();

// on détruit la session côté serveur
session_destroy();

// on détruit le cookie de session côté client
setcookie(session_name(), 'false', 1, '/');

//on détruit les cookies de remember me
setcookie('rememberme_data', false, 1);
setcookie('rememberme_token', false, 1);


echo '<div class="alert alert-success" role="success">Déconnexion réussie</div>';
echo redirectJS('index.php', 2);

include_once 'footer.php';