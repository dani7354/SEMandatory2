<?php

session_start();

if( isset($_SESSION['user_id']) ){
	header("Location: /php-login");
}

require 'database.php';

if(!empty($_POST['email']) && !empty($_POST['password'])):
	
	$email = $_POST['email'];
	$pass = $_POST['password'];

	$query="SELECT id, email, password FROM users WHERE email = '".$email."' and password = '".$pass."'";
	
	$records = $conn->prepare($query);
	$records->execute();
	$results = $records->fetch(PDO::FETCH_ASSOC);
	
	$message = '';

	if ($results != 0){

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
		
		<input type="text" placeholder="Enter your email" name="email">
		<input type="password" placeholder="and password" name="password">

		<input type="submit">

	</form>

</body>
</html>