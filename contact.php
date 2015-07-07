<?php
include_once 'header.php';

//debug($_POST);

/*
$lastname = '';
if (!empty($_POST['lastname'])) {
	$lastname = $_POST['lastname'];
}
*/

$lastname = !empty($_POST['lastname']) ? $_POST['lastname'] : '';
$firstname = !empty($_POST['firstname']) ? $_POST['firstname'] : '';
$email = !empty($_POST['email']) ? $_POST['email'] : '';
$message = !empty($_POST['message']) ? $_POST['message'] : '';
$newsletter = !empty($_POST['newsletter']) ? intval($_POST['newsletter']) : 0;

$errors = array();

// On a appuyé sur le bouton Envoyer, le formulaire a été soumis
if (!empty($_POST)) {

	if (empty($lastname)) {
		$errors['lastname'] = 'Vous devez renseigner votre nom';
	}
	if (empty($firstname)) {
		$errors['firstname'] = 'Vous devez renseigner votre prénom';
	}
	if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$errors['email'] = 'Vous devez renseigner un email valide';
	}
	if (empty($message)) {
		$errors['message'] = 'Vous devez renseigner votre message';
	}

	if (empty($errors)) {

		$query = $db->prepare('INSERT INTO contact (lastname, firstname, email, newsletter, message, date) VALUES (:lastname, :firstname, :email, :newsletter, :message, NOW())');
		$query->bindValue('lastname', $lastname);
		$query->bindValue('firstname', $firstname);
		$query->bindValue('email', $email);
		$query->bindValue('message', $message);
		$query->bindValue('newsletter', $newsletter, PDO::PARAM_INT);
		$query->execute();

		$result = $db->lastInsertId();

		if (empty($result)) {
			echo '<div class="alert alert-danger" role="danger">Une erreur est survenue</div>';
		} else {
			echo '<div class="alert alert-success" role="success">Merci :)</div>';
		}
		goto end;
	}
}
?>

<h1>Contact</h1>

<?php if (!empty($errors)) { ?>
<div class="alert alert-danger" role="danger">
	<?php
	foreach ($errors as $error) {
		echo $error.'<br>';
	}
	?>
</div>
<?php } ?>

<form class="form-horizontal" action="" method="POST" novalidate>

	<div class="form-group<?= !empty($errors['lastname']) ? ' has-error' : '' ?>">
		<label for="lastname" class="col-sm-2 control-label">Nom</label>
		<div class="col-sm-3">
			<input type="text" id="lastname" name="lastname" class="form-control" placeholder="Nom" value="<?= $lastname ?>">
		</div>
	</div>

	<div class="form-group<?= !empty($errors['firstname']) ? ' has-error' : '' ?>">
		<label for="firstname" class="col-sm-2 control-label">Prénom</label>
		<div class="col-sm-3">
			<input type="text" id="firstname" name="firstname" class="form-control" placeholder="Prénom" value="<?= $firstname ?>">
		</div>
	</div>

	<div class="form-group<?= !empty($errors['email']) ? ' has-error' : '' ?>">
		<label for="email" class="col-sm-2 control-label">Email</label>
		<div class="col-sm-5">
			<input type="email" id="email" name="email" class="form-control" placeholder="Email" value="<?= $email ?>">
		</div>
	</div>

	<div class="form-group<?= !empty($errors['message']) ? ' has-error' : '' ?>">
		<label for="message" class="col-sm-2 control-label">Message</label>
		<div class="col-sm-3">
			<textarea id="message" name="message" class="form-control" placeholder="Your message"><?= $message ?></textarea>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<div class="checkbox">
				<label>
					<input type="checkbox" name="newsletter" value="1" <?= $newsletter ? 'checked' : '' ?>> S'abonner à la newsletter
				</label>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-default">Envoyer</button>
		</div>
	</div>
</form>

<?php
end:

include_once 'footer.php';
?>