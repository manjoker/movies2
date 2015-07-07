	<!-- NAVBAR -->
	<nav class="navbar navbar-fixed-top navbar-inverse">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.php">Movies</a>
			</div>
			<div id="navbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<?php foreach ($pages as $page_url => $page_name) {
						$active = '';
						if ($current_page == $page_url) {
							$active = ' class="active"';
						}
					?>
					<li<?= $active ?>><a href="<?= $page_url ?>"><?= $page_name ?></a></li>
					<?php } ?>
				</ul>
				<form class="navbar-form navbar-right" action="search.php" method="GET">
					<div class="input-group">
						<input name="search" type="text" class="form-control" placeholder="Recherche rapide...">
						<span class="input-group-btn">
							<button class="btn btn-default" type="submit">
								<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
							</button>
						</span>
					</div>
				</form>
				<ul class="nav navbar-nav navbar-right">
					<?php 
						if (!empty($_SESSION['user_id'])) { ?>
						<li><a>Hi <?= $_SESSION['firstname'] ?></a></li>
						<li><a href="logout.php">Déconnecxion</a></li>
						<?php } else { ?>
                    <li><a href="login.php">Connexion</a></li>
                    <li><a href="register.php">Inscription</a></li>
					<?php } ?>
                </ul>
			</div>
		</div>
	</nav>
	<!-- END NAVBAR -->