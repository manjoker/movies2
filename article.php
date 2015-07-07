<?php
include_once 'header.php';

if (!isset($_GET['id'])) {
	exit('Undefined param id');
}

$id = intval($_GET['id']);

$query = $db->prepare('SELECT * FROM news WHERE news_id = :id');
$query->bindValue(':id', $id, PDO::PARAM_INT);
$query->execute();
$article = $query->fetch();

if (empty($article)) {
	exit('Undefined news');
}

$back_link = 'news.php';
if (!empty($_SERVER['HTTP_REFERER'])) {
	$back_link = $_SERVER['HTTP_REFERER'];
}
?>

<a href="<?= $back_link ?>">&laquo; Retour</a>

<hr>

<h2><?= ucfirst($article['news_title']) ?></h2>
<h4><?= date('d/m/Y H:i:s', strtotime($article['news_date']))  ?></h4>
<p>
	<?= nl2br($article['news_text']) ?>
</p>

<?php include_once 'footer.php'; ?>