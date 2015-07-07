<?php require_once 'header.php' ?>

		<div class="row">

			<div class="col-xs-12 col-sm-9">

				<div class="jumbotron">
					<h1>Bienvenue sur Movies !</h1>
					<p>Le site n°1 du cinéma.<br />
						Découvrez notre <a href="search.php">recherche</a> de films et des <a href="news.php">actualités</a> sur le cinéma.
					</p>
				</div><!-- .jumbotron -->

				<div class="row marketing">

					<?php for ($i = 0; $i < 3; $i++) {  ?>
					<!-- BLOCK RANDOM MOVIE -->
					<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
						<img class="movie-thumbnail" src="img/cover.png" />
						<div class="caption">
							<h2>Movie title</h2>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed est urna, facilisis ac ipsum aliquet, tincidunt molestie orci. Fusce imperdiet elementum risus in fermentum.</p>
							<p><a class="btn btn-default" href="movie.php" role="button">Voir la fiche du film &raquo;</a></p>
						</div>
					</div>
					<!-- END BLOCK RANDOM MOVIE -->
					<?php } ?>

				</div><!-- .marketing -->

				<hr>

				<div id="top-movies" class="row">

					<?php for ($i = 0; $i < 8; $i++) { ?>
					<!-- BLOCK TOP MOVIE -->
					<div class="top-movie col-xs-12 col-sm-6 col-md-4 col-lg-3">
						<div class="thumbnail">
							<img src="img/cover.png" />
							<div class="caption">
								<h2>Movie title</h2>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed est urna, facilisis ac ipsum aliquet, tincidunt molestie orci. Fusce imperdiet elementum risus in fermentum.</p>
								<p><a class="btn btn-default" href="movie.php" role="button">Voir la fiche du film &raquo;</a></p>
							</div>
						</div>
					</div>
					<!-- END BLOCK TOP MOVIE -->
					<?php } ?>

				</div><!-- #top-movies -->

			</div><!-- .col-xs-12.col-sm-9 -->

			<?php include 'sidebar.php' ?>

		</div><!-- .row -->

<?php include_once 'footer.php' ?>