<?php
$same_genres_movies = getSimilarMovies($movie, 'genres');
$same_actors_movies = getSimilarMovies($movie, 'actors');
$same_directors_movies = getSimilarMovies($movie, 'directors');
$same_writers_movies = getSimilarMovies($movie, 'writers');
?>
	<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar">

		<?php
		echo displayList($same_genres_movies, 'Films des mêmes genres', 'movie.php', 'primary');
		echo displayList($same_actors_movies, 'Films des mêmes acteurs', 'movie.php', 'info');
		echo displayList($same_directors_movies, 'Films des mêmes réalisateurs', 'movie.php', 'default');
		echo displayList($same_writers_movies, 'Films des mêmes auteurs', 'movie.php', 'warning');
		//echo displayList($same_writers_movies, 'Les 4 derniers films visités', 'movie.php', 'warning');
		?>

	</div><!-- #sidebar -->