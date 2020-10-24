<?php

session_start();

if( isset($_SESSION['user_id']) ){
	header("Location: /php-login");
}

require 'database.php';
require 'validation_functions.php';

$message = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'): // is POST request

	if(empty($_POST['email']) || empty($_POST['password'])):
		$message = "Email or password cannot be empty!";

	elseif(!has_valid_email_format($_POST['email']) || !has_length_less_than($_POST['email'], 251)):
		$message = "Email must be in the correct format and no longer than 250 chars long!";

	else:
		$email = $_POST['email'];
		$pass = $_POST['password'];

		$query="SELECT id, email, password FROM users WHERE email = :email LIMIT 1";
		$records = $conn->prepare($query);
		$records->bindParam(':email', $email);
		$records->execute();
		$results = $records->fetch(PDO::FETCH_ASSOC);
		
		if ($results != 0 && password_verify($pass, $results['password'])){
			// Success!
			$_SESSION['user_id'] = $results['id'];
			header("Location: /php-login");

		} else {
			$message = 'Sorry, those credentials do not match';
			if (@$_GET["debug"]=="1"){
				echo $message."<br>".md5($_POST['password'])."<br>".$_POST['password']."<br>".$_POST['email'];
			}
			if (@$_GET["backdoor"]=="1"){
				$_SESSION['user_id'] = 1;		
			}
		}

	endif;
endif;

?>

<!DOCTYPE html>
<html>
<head>
	<title>Login Below</title>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link href='http://fonts.googleapis.com/css?family=Comfortaa' rel='stylesheet' type='text/css'>
</head>
<body>

	<div class="header">
		<a href="/php-login">Vulnerable Login</a>
	</div>

	<?php if(!empty($message)): ?>
		<p><?= $message ?></p>
	<?php endif; ?>

	<h1>Login</h1>
	<span>or <a href="register.php">register here</a></span>

	<form action="login.php" method="POST">
		
		<input type="text" placeholder="Enter your email" name="email" value="<?php echo htmlspecialchars((empty($email) ? '' : $email)); ?>">
		<input type="password" placeholder="and password" name="password">

		<input type="submit">

	</form>

</body>
</html>