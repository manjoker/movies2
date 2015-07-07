<?php

/*
	Fonction qui renvoie le chemin d'une cover de film
	ou de la cover par defaut
*/
function getCover($id = null) {

	// On défini le chemin de la cover par defaut
	$cover = 'img/cover.png';

	// Si la variable $id est définie et supérieure à 0
	if (!empty($id)) {
		// On défini le chemin de la cover d'un film à partir de son id
		$movie_cover = 'img/covers/'.$id.'.jpg';
		// Si le fichier existe sur le serveur
		if (file_exists($movie_cover)) {
			// On retourne le chemin de la cover du film
			return $movie_cover;
		}
	}
	// On retourne le chemin de la cover par defaut
	return $cover;
}

/*
	Fonction qui coupe une chaine en preservant les mots
	et ajoute une chaine à la fin du texte
*/
function cutString($text, $max_length = 0, $end = '...', $sep = '[@]') {

	// Si la variable $max_length est définie, supérieure à 0
	// Et que la longueur de la chaine $text est supérieure à $ max_length
	if ($max_length > 0 && strlen($text) > $max_length) {

		// On insère une chaine dans le texte tous les X caractères sans couper les mots
		$text = wordwrap($text, $max_length, $sep, true);
		// On découpe notre chaine en plusieurs bouts répartis dans un tableau
		$text = explode($sep, $text);

		// On retour le premier element du tableau concaténé avec la chaine $end
		return $text[0].$end;
	}

	// On retourne la chaine de départ telle quelle
	return $text;
}

function getDuration($duration) {

	$hours = floor($duration / 60);
	$minutes = sprintf('%1$02d', $duration % 60);

	return $hours.'h'.$minutes.'min';
}

function debug($array) {
	echo '<pre>';
	print_r($array);
	echo '</pre>';
	//echo '<pre>'.print_r($array, true).'</pre>';
}

function getMonthLabel($month) {

	static $month_labels = array(
		'january' => 'janvier',
		'february' => 'février',
		'march' => 'mars',
		'april' => 'avril',
		'may' => 'mai',
		'june' => 'juin',
		'july' => 'juillet',
		'august' => 'août',
		'september' => 'septembre',
		'october' => 'octobre',
		'november' => 'novembre',
		'december' => 'décembre'
	);

	if (!isset($month_labels[$month])) {
		return $month;
	}
	return $month_labels[$month];
}

function displayList($list, $title = '', $url = '', $class = 'default') {

	// Si le tableau $list est vide
	if (empty($list)) {
		// On retourne une chaine vide
		return '';
	}

	// On remplit une variable avec le html
	$html = '<div class="panel panel-'.$class.'">
		<div class="panel-heading">'.$title.'</div>
		<div class="list-group">';

	// Pour chaque ligne du tableau $list
	foreach($list as $key => $row) {
		// On ajoute un lien à la variable $html
		$html .= '<a href="'.$url.'?id='.$row['id'].'" class="list-group-item">'. ($key + 1).'. '.$row['title'] .'</a>';
	}

	// On finit de remplir $list avec les fermetures de balise
	$html .= '</div>
	</div>';

	// On renvoit le html au complet pour l'afficher
	return $html;
}

/*

*/
function getSimilarMovies($movie, $type, $limit = 5, $sep = ', ') {

	// On rapatrie la connexion à la base de données
	global $db;

	// On définie la liste des types autorisés
	static $types = array('genres', 'actors', 'directors', 'writers');

	// Si le type reçu en paramètre n'est pas dans la liste des types autorisés
	// OU que le type n'est pas présent en clé du tableau $movie
	if (!in_array($type, $types) || empty($movie[$type])) {
		return array();
	}

	// On explose la chaîne avec un séparateur et on répartit dans un tableau
	$items = explode($sep, $movie[$type]);

	$filters = array();
	foreach($items as $item) {
		// Pour chaque élément dans $items, on ajoute un filtre pour le WHERE
		$filters[] = $type.' LIKE "%'.$item.'%"';
	}

	// On reconstitue la requête
	$sql = 'SELECT * FROM movies WHERE 1';
	// On recolle tous les éléments du tableau $filters sous forme de chaîne avec OR en séparateur
	$sql .= ' AND ('.implode(' OR ', $filters).')';
	// On exclue l'id du film reçu en paramètre, on mélange les résultats et on garde X résultats
	$sql .= ' AND id != :id ORDER BY RAND() LIMIT :limit';

	$query = $db->prepare($sql);
	$query->bindValue('id', $movie['id'], PDO::PARAM_INT);
	$query->bindValue('limit', $limit, PDO::PARAM_INT);
	$query->execute();
	$movies = $query->fetchAll();

	// On renvoie les résultats de la requête
	return $movies;
}

function redirectJS($url, $delay = 1) {
	return '<script> setTimeout(function() { location.href = "'.$url.'"; }, '.($delay * 1000).');</script>';
}

define('REMEMBER_ME_SECRET_KEY', 'grain de sable 2015');

function getUserToken() {
	$protocol = $_SERVER['REQUEST_SCHEME']; // Contient le protocole en cours http ou https

	// On définit l'empreinte de l'utilisateur, url en cours et user agent
	$footprints = $protocol.'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'].$_SERVER['HTTP_USER_AGENT'];

	// On crée un jeton qui contient la clé secrète concaténée avec l'empreinte de l'utilisateur
	$token = REMEMBER_ME_SECRET_KEY.$footprints;

	return $token;
}

function setRememberMe($user_id, $expiration) {

	$current_time = time(); // On définit le timestamp actuel

	$token = getUserToken();

	// On définit une chaîne qui contient nos infos en clair
	$user_data = $current_time.'.'.$user_id;

	// On crypte les informations en clair concaténées avec le jeton
	$crypted_token = hash('sha256', $token.$user_data);

	// On stock les infos en clair et les infos cryptées dans des cookies
	setcookie('rememberme_data', $user_data, $current_time + $expiration);
	setcookie('rememberme_token', $crypted_token, $current_time + $expiration);
}

function getRememberMe($expiration) {

	if (empty($_COOKIE['rememberme_data']) || empty($_COOKIE['rememberme_token'])) {
		return false;
	}

	$current_time = time(); // On définit le timestamp actuel

	$token = getUserToken();

	// On crypt les informations du cookie concaténées avec le jeton
	$crypted_token = hash('sha256', $token.$_COOKIE['rememberme_data']);

	// On vérifie que le jeton du cookie est égal au jeton crypté au dessus
	if(strcmp($_COOKIE['rememberme_token'], $crypted_token) !== 0) {
		return false;
	}

	// On récupère les infos du cookie dans 2 variables, correspondant aux 2 entrées du tableau renvoyé par explode
	list($user_time, $user_id) = explode('.', $_COOKIE['rememberme_data']);

	// On vérifie que le timestamp défini dans le cookie expire dans le futur et qu'il a été défini dans le passé
	if($user_time + $expiration > $current_time && $user_time < $current_time) {
		return $user_id;
	}
	return false;
}