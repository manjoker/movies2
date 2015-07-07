<?php
include_once 'header.php';

// On réceptionne un paramètre ?p= passé dans l'URL

$page = !empty($_GET['p']) ? intval($_GET['p']) : 1;
$page = $page > 0 ? $page : 1;

$nb_items_per_page = 10;

$min_max_year = $db->query('SELECT MIN(year) as min_year, MAX(year) as max_year FROM movies')->fetch();

$range_years = range($min_max_year['max_year'], $min_max_year['min_year']);

$movie_years = array();
$results = $db->query('SELECT DISTINCT(year) FROM movies ORDER BY year DESC')->fetchAll();
foreach($results as $result) {
	$movie_years[] = $result['year'];
}

$movie_genres = $db->query('SELECT * FROM genres ORDER BY genre_name ASC')->fetchAll();

$title = !empty($_GET['title']) ? $_GET['title'] : '';
$genre = !empty($_GET['genre']) ? $_GET['genre'] : '';
$year = !empty($_GET['year']) ? $_GET['year'] : '';
$actors = !empty($_GET['actors']) ? $_GET['actors'] : '';
$directors = !empty($_GET['directors']) ? $_GET['directors'] : '';

?>
<h1>Recherche</h1>

<hr>

<form class="form-inline" action="search.php" method="GET">
	<div class="form-group">
		<label for="title">Titre</label>
		<input type="text" id="title" name="title" class="form-control" placeholder="Star Wars" value="<?= $title ?>">
	</div>

	<div class="form-group">
		<label for="title">Genre</label>
		<select id="genre" name="genre" class="form-control">
			<option value="">...</option>
			<?php
			foreach($movie_genres as $movie_genre) {
				/*
				$selected = '';
				if ($movie_genre['genre_label'] == $genre) {
					$selected = ' selected="selected"';
				}
				*/
				$selected = $movie_genre['genre_label'] == $genre ? ' selected="selected"' : '';
			?>
			<option value="<?= $movie_genre['genre_label'] ?>"<?= $selected ?>><?= $movie_genre['genre_name'] ?></option>
			<?php } ?>
		</select>
	</div>

	<div class="form-group">
		<label for="title">Année</label>
		<select id="year" name="year" class="form-control">
			<option value="">...</option>
			<?php
			//for ($year = $current_year; $year >= 1900; $year--) {
			foreach($range_years as $movie_year) {
				$inactive = '';
				if (!in_array($movie_year, $movie_years)) {
					$inactive = ' disabled="disabled" class="disabled"';
				}
				/*
				$selected = '';
				if ($movie_year == $year) {
					$selected = ' selected="selected"';
				}
				*/
				$selected = $movie_year == $year ? ' selected="selected"' : '';
			?>
			<option value="<?= $movie_year ?>"<?= $inactive.$selected ?>><?= $movie_year ?></option>
			<?php } ?>
		</select>
	</div>

	<div class="form-group">
		<label for="title">Acteur</label>
		<input type="text" id="actors" name="actors" class="form-control" placeholder="Christopher Lloyd" value="<?= $actors ?>">
	</div>

	<div class="form-group">
		<label for="title">Réalisateur</label>
		<input type="text" id="directors" name="directors" class="form-control" placeholder="Robert Zemeckis" value="<?= $directors ?>">
	</div>

	<div class="form-group">
		<button type="submit" class="btn btn-default">
			<span class="glyphicon glyphicon-search" aria-hidden="true"></span> Rechercher
		</button>
	</div>
</form>

<?php

if (!empty($_GET)) {

	$count_results = 0;
	$search_results = array();

	if (!empty($_GET['search'])) {

		$search = $_GET['search'];

		$filter = ' WHERE title LIKE :search OR synopsis LIKE :search OR actors LIKE :search';

		$query = $db->prepare('SELECT COUNT(*) as count_total FROM movies '.$filter);
		$query->bindValue('search', '%'.$search.'%');
		$query->execute();
		$result = $query->fetch();
		$count_results = $result['count_total'];

		$query = $db->prepare('SELECT * FROM movies '.$filter.' LIMIT :start, :nb_items');
		$query->bindValue('search', '%'.$search.'%');
		$query->bindValue('start', ($page - 1) * $nb_items_per_page, PDO::PARAM_INT);
		$query->bindValue('nb_items', $nb_items_per_page, PDO::PARAM_INT);
		$query->execute();
		$search_results = $query->fetchAll();

	} else {

		$bindings = array();

		$select_count = 'SELECT COUNT(*) as count_total ';
		$select = 'SELECT * ';

		$from = ' FROM movies ';

		$where = ' WHERE 1 ';

		if (!empty($title)) {
			$where .= ' AND title LIKE :title';
			$bindings['title'] = '%'.$title.'%';
		}
		if (!empty($genre)) {
			$where .= ' AND genres LIKE :genre';
			$bindings['genre'] = '%'.$genre.'%';
		}
		if (!empty($year)) {
			$where .= ' AND year = :year';
			$bindings['year'] = $year;
		}
		if (!empty($actors)) {
			$where .= ' AND actors LIKE :actors';
			$bindings['actors'] = '%'.$actors.'%';
		}
		if (!empty($directors)) {
			$where .= ' AND directors LIKE :directors';
			$bindings['directors'] = '%'.$directors.'%';
		}

		$limit = ' LIMIT :start, :nb_items ';

		if (!empty($bindings)) {

			$sql_count = $select_count . $from . $where;
			$sql = $select . $from . $where . $limit;

			// Requête de comptage du nombre total de résultats de recherche
			$query = $db->prepare($sql_count);
			foreach($bindings as $key => $value) {
				$query->bindValue($key, $value);
			}
			$query->execute();
			$result = $query->fetch();
			$count_results = $result['count_total'];

			// Requête de récupération des résultats de recherche paginés
			$query = $db->prepare($sql);
			$bindings['start'] = ($page - 1)  * $nb_items_per_page;
			$bindings['nb_items'] = $nb_items_per_page;
			foreach($bindings as $key => $value) {
				$type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
				$query->bindValue($key, $value, $type);
			}
			$query->execute();
			$search_results = $query->fetchAll();

		}
	}

	// On calcul le nombre de pages et on arrondi à l'entier supérieur
	$nb_pages = ceil($count_results / $nb_items_per_page);
?>
<hr>

<h3><?= $count_results ?> résultat(s) pour la recherche <?= !empty($search) ? '"'.$search.'"' : '' ?></h3>

<br>

<div class="search-results list-group">

	<?php
	include 'pagination.php';

	foreach($search_results as $movie) {
	?>
	<a href="movie.php?id=<?= $movie['id'] ?>" class="list-group-item">
		<img height="80" width="60" class="movie-cover" src="<?= getCover($movie['id']) ?>" align="left">
		<h4 class="list-group-item-heading"><?= $movie['title'] ?> (<?= $movie['year'] ?>)</h4>
		<p class="list-group-item-text">
			<?= $movie['genres'] ?>
			<br>
			<?= $movie['actors'] ?>
		</p>
	</a>
	<?php
	}

	include 'pagination.php';
	?>

</div>
<?php
} // end if !empty($_GET)

include_once 'footer.php';
?>