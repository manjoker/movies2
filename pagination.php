				<?php
				// On crée une copie de $_GET
				$get = $_GET;
				// Si on a déjà un paramètre p dans l'url
				if (isset($get['p'])) {
					// On retire le paramètre p pour éviter les doublons
					unset($get['p']);
				}
				// On reconsitue les paramètres d'url sous forme de chaine
				$querystring = '&'.http_build_query($get);
				?>
				<nav>
					<ul class="pagination">
					<?php
					if ($page > 1) {
					?>
					<li>
						<a href="?p=1<?= $querystring ?>">
							<span class="glyphicon glyphicon-step-backward"></span>
						</a>
						<a href="?p=<?= $page - 1 ?><?= $querystring ?>">
							<span class="glyphicon glyphicon-chevron-left"></span>
						</a>
					</li>
					<?php
					}

					$nb_pages_active = 3;

					for ($i = max($page - $nb_pages_active, 1); $i <= max(1, min($nb_pages, $page + $nb_pages_active)); $i++) {

						/*
						$isCurrentPage = $i == ($page + 1);

						$active = $isCurrentPage ? ' class="active"' : '';
						$href = !$isCurrentPage ? ' href="?p='.$i.'"' : '';

						echo '<li'.$active.'><a'.$href.'>'.$i.'</a></li>';
						*/
						if ($i == $page) {
							echo '<li class="active"><a>'.$i.'</a></li>';
						} else {
							echo '<li><a href="?p='.$i.$querystring.'">'.$i.'</a></li>';
						}
					}

					if ($page < $nb_pages) {
					?>
					<li>
						<a href="?p=<?= $page + 1 ?><?= $querystring ?>">
							<span class="glyphicon glyphicon-chevron-right"></span>
						</a>
						<a href="?p=<?= $nb_pages ?><?= $querystring ?>">
							<span class="glyphicon glyphicon-step-forward"></span>
						</a>
					</li>
					<?php } ?>
					</ul>
				</nav>