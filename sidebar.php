			<?php
			$top_rating_movies = $db->query('SELECT id, title FROM movies ORDER BY rating DESC LIMIT 5')->fetchAll();
			$most_recent_movies = $db->query('SELECT id, title FROM movies ORDER BY year DESC LIMIT 5')->fetchAll();
			$random_movies = $db->query('SELECT id, title FROM movies ORDER BY RAND() LIMIT 5')->fetchAll();
			$last_news = $db->query('SELECT news_id as id, news_title as title FROM news ORDER BY news_date DESC LIMIT 5')->fetchAll();
			?>

			<!-- SIDEBAR -->
			<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar">
				<?php
				echo displayList($top_rating_movies, 'Les 5 films les mieux notés', 'movie.php', 'primary');
				echo displayList($most_recent_movies, 'Les 5 films les plus récents', 'movie.php', 'info');
				echo displayList($random_movies, '5 films au hasard', 'movie.php', 'warning');
				echo displayList($last_news, 'Les dernières actualités', 'article.php');
				?>
			</div>
			<!-- END SIDEBAR -->