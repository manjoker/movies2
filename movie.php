<?php
include_once 'header.php';

if (!isset($_GET['id'])) {
	exit('Undefined param id');
}

$id = intval($_GET['id']);

$query = $db->prepare('SELECT * FROM movies WHERE id = :id');
$query->bindValue(':id', $id, PDO::PARAM_INT);
$query->execute();
$movie = $query->fetch();

include_once 'movie-common.php';