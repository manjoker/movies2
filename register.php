<?php
include_once 'header.php';

define('FACEBOOK_SDK_V4_SRC_DIR', 'inc/facebook/src/Facebook/');
require __DIR__ . '/inc/facebook/autoload.php';

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookGraphObject;
use Facebook\GraphUser;
// Use the login url on a link or button to 
// redirect to Facebook for authentication

FacebookSession::setDefaultApplication('502151559942833', 'd29895a1c737574e37883e95dca475fd');


$helper = new FacebookRedirectLoginHelper($root_path.'/register.php');
try {
  $session = $helper->getSessionFromRedirect();
} catch(FacebookRequestException $ex) {
  // When Facebook returns an error
} catch(\Exception $ex) {
  // When validation fails or other local issues
}
if (!empty($session)) {
 	$request = new FacebookRequest($session, 'GET', '/me');
	$response = $request->execute();
	$graphObject = $response->getGraphObject();

	debug($graphObject);

	$user = $response->getGraphObject(GraphUser::className());

	$fb_id = $user->getProperty('id');
	$firstname = $user->getProperty('first_name');
	$lastname = $user->getProperty('last_name');
	$email = $user->getProperty('email');

	if (!empty($email)) {

		$query = $db->prepare('SELECT * FROM users WHERE fb_id = :fb_id');
		$query->bindValue('fb_id', $fb_id);
		$query->execute();
		$fb_user = $query->fetch();

		if (!empty($fb_user)) {
			
			$_SESSION['user_id'] = $fb_user['id'];
			$_SESSION['firstname'] = $fb_user['firstname'];
			header('location: index.php');
			exit();
		}

		$query = $db->prepare('INSERT INTO users SET firstname = :firstname, lastname = :lastname, email = :email, fb_id = :fb_id, register_date = NOW()
		ON DUPLICATE KEY UPDATE firstname = :firstname, lastname = :lastname, email = :email, fb_id = :fb_id');
		$query->bindValue('fb_id', $fb_id);
		$query->bindValue('firstname', $firstname);
		$query->bindValue('lastname', $lastname);
		$query->bindValue('email', $email);
		$query->execute();

		

		$user_id = $db->lastInsertId();

		debug($user_id);

		if (empty($user_id)) {
			echo '<div class="alert alert-danger" role="danger">Une erreur est survenue</div>';
		} else {
			$_SESSION['user_id'] = $user_id;
			$_SESSION['firstname'] = $firstname;

			header('Location: index.php');
			exit();
		}
	}
}

//debug($_POST);


$lastname = !empty($_POST['lastname']) ? $_POST['lastname'] : '';
$firstname = !empty($_POST['firstname']) ? $_POST['firstname'] : '';
$email = !empty($_POST['email']) ? $_POST['email'] : '';
$confirm_email = !empty($_POST['confirm_email']) ? $_POST['confirm_email'] : '';
$password = !empty($_POST['password']) ? $_POST['password'] : '';
$confirm_password = !empty($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
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
	if (strcmp($email, $confirm_email) !== 0) {
		$errors['confirm_email'] = 'Vous devez confirmer votre email';
	}
	if (strlen($password) < 6) {
		$errors['password'] = 'Vous devez fournir un mot de passe de 6 caractères minimum';
	}
	if (strcmp($password, $confirm_password) !== 0) {
		$errors['confirm_password'] = 'Vous devez confirmer votre mot de passe';
	}

	if (empty($errors)) {

		$query = $db->prepare('SELECT * FROM users WHERE email = :email');
		$query->bindValue('email', $email);
		$query->execute();
		$user = $query->fetch();

		if (!empty($user)) {
			$errors['email'] = "L'email est déjà pris";
		} else {

			$crypted_password = password_hash($password, PASSWORD_BCRYPT);

			$query = $db->prepare('INSERT INTO users (lastname, firstname, email, pass, newsletter, register_date) VALUES (:lastname, :firstname, :email, :password, :newsletter, NOW())');
			$query->bindValue('lastname', $lastname);
			$query->bindValue('firstname', $firstname);
			$query->bindValue('email', $email);
			$query->bindValue('password', $crypted_password);
			$query->bindValue('newsletter', $newsletter, PDO::PARAM_INT);
			$query->execute();

			$user_id = $db->lastInsertId();

			if (empty($user_id)) {
				echo '<div class="alert alert-danger" role="danger">Une erreur est survenue</div>';
			} else {
				$_SESSION['user_id'] = $user_id;
				$_SESSION['firstname'] = $user['firstname'];


				echo '<div class="alert alert-success" role="success">Authentification réussie</div>';
				echo redirectJS('index.php', 2);
			}
			goto end;
		}
	}
}
?>

<h1>Inscription</h1>

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

	<div class="form-group<?= !empty($errors['confirm_email']) ? ' has-error' : '' ?>">
		<label for="confirm_email" class="col-sm-2 control-label">Confirmation d'email</label>
		<div class="col-sm-5">
			<input type="email" id="confirm_email" name="confirm_email" class="form-control" placeholder="Confirmation d'email" value="<?= $confirm_email ?>">
		</div>
	</div>

	<div class="form-group<?= !empty($errors['password']) ? ' has-error' : '' ?>">
		<label for="password" class="col-sm-2 control-label">Mot de passe</label>
		<div class="col-sm-5">
			<input type="password" id="password" name="password" class="form-control" placeholder="Mot de passe" value="<?= $password ?>">
		</div>
	</div>

	<div class="form-group<?= !empty($errors['confirm_password']) ? ' has-error' : '' ?>">
		<label for="confirm_password" class="col-sm-2 control-label">Confirmation de mot de passe</label>
		<div class="col-sm-5">
			<input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirmation de mot de passe" value="<?= $confirm_password ?>">
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








