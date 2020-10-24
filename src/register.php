<?php

session_start();

if( isset($_SESSION['user_id']) ){
	header("Location: /php-login");
}

require 'database.php';
require 'validation_functions.php';

$message = '';

if($_POST['password'] != $_POST['confirm_password']):
	$message = 'Passwords do not match!';

elseif(empty($_POST['password']) || !has_length_greater_than($_POST['password'], 7)):
	$message = 'Password needs to be at least 8 characters!';

elseif(!has_valid_email_format($_POST['email']) || !has_length_less_than($_POST['email'], 251)):
	$message = 'The entered email is invalid! It should be in the correct format and no longer than 250 chars long...';
else:
	// Enter the new user in the database
	$password_hash = password_hash($_POST['password'], PASSWORD_BCRYPT, ["cost" => 13]);
	$sql = "INSERT INTO users (email, password) VALUES (:email, :pw)";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':email', $_POST['email']);
	$stmt->bindParam(':pw', $password_hash);
	
	if( $stmt->execute() ):
		$message = 'Successfully created new user';
	else:
		$message = 'Sorry there must have been an issue creating your account';
	endif;
endif;

?>

<!DOCTYPE html>
<html>
<head>
	<title>Register Below</title>
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

	<h1>Register</h1>
	<span>or <a href="login.php">login here</a></span>

	<form action="register.php" method="POST">
		
		<input type="text" placeholder="Enter your email" name="email">
		<input type="password" placeholder="and password" name="password">
		<input type="password" placeholder="confirm password" name="confirm_password">
		<input type="submit">

	</form>

</body>
</html>