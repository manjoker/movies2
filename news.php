<?php
include_once 'header.php';

// On récupère un paramètre date depuis l'url
$date = !empty($_GET['date']) ? $_GET['date'] : '';

$bindings = array();
$filter_date = '';
if (!empty($date)) {
	$filter_date = ' AND DATE_FORMAT(news_date, "%Y-%m") = :date';
	$bindings['date'] = $date;
}

// On défini la page sur laquelle on se trouve
$page = !empty($_GET['p']) ? intval($_GET['p']) : 1;
// Si page > 0 on garde page, sinon on prend 1
$page = $page > 0 ? $page : 1;

// On défini le nombre d'éléments à afficher par page
$nb_items_per_page = 5;

// On fait une requête qui compte le nombre total de résultats à afficher
$query = $db->prepare('SELECT COUNT(*) as count_news FROM news WHERE 1 '.$filter_date);
foreach($bindings as $key => $value) {
	$type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
	$query->bindValue($key, $value, $type);
}
$query->execute();
$result = $query->fetch();
$count_news = $result['count_news'];

// On calcul le nombre de pages pour construire les liens de pagination
// ceil() permet d'arrondir à l'entier supérieur
$nb_pages = ceil($count_news / $nb_items_per_page);

// On fait la requête qui va chercher la portion qui nous intéresse
// On renvoie les résultats depuis la ligne X, et on garde N éléments
$query = $db->prepare('SELECT * FROM news WHERE 1 '.$filter_date.' ORDER BY news_date DESC LIMIT :start, :nb_items');
$bindings['start'] = ($page - 1)  * $nb_items_per_page;
$bindings['nb_items'] = $nb_items_per_page;

foreach($bindings as $key => $value) {
	$type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
	$query->bindValue($key, $value, $type);
}

$query->execute();
$news = $query->fetchAll();
?>
<div class="news-container">

	<div class="news-header">
		<h1>Actualités</h1>
		<p>Découvrez toute l'actualité du cinéma</p>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-9">

			<?php
			include 'pagination.php';

			foreach($news as $key => $article) { ?>
			<!-- NEWS POST -->
			<div class="news-post">

				<h2>
					<a href="article.php?id=<?= $article['news_id'] ?>"><?= ucfirst($article['news_title']) ?></a>
				</h2>
				<p><?= date('d/m/Y H:i:s', strtotime($article['news_date']))  ?></p>
				<hr>
				<blockquote>
					<p>
					<?= nl2br(cutString($article['news_text'], 100)) ?>
					</p>
				</blockquote>

				<a href="article.php?id=<?= $article['news_id'] ?>">
					Lire la suite
				</a>

			</div>
			<!-- END NEWS POST -->
			<?php
			}

			include 'pagination.php';
			?>

		</div>

		<?php include 'sidebar-news.php'; ?>

	</div>
</div>

<?php include_once 'footer.php'; ?>