<?php
include_once 'header.php';

$query = $db->query('SELECT * FROM movies ORDER BY RAND() LIMIT 1');
$movie = $query->fetch();

include_once 'movie-common.php';