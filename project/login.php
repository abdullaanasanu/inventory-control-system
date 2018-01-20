<?php

include('classes/DB.php');
include('./classes/Login.php');

if (Login::isLoggedIn()) {
	header('Location: index.php');
}

/*	Password Generation

$password = 1234567890;
echo password_hash($password, PASSWORD_BCRYPT);*/

if (isset($_POST['login'])) {
	
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	if (DB::query('SELECT username FROM users WHERE username = :username', array(':username'=>$username))) {
		
		if(password_verify($password, DB::query('SELECT password FROM users WHERE username = :username', array(':username'=>$username))[0]['password'])) {			
			
			echo "logged In successfully!";
			
			$cstrong = True;
			$token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
			
			$user_id = DB::query('SELECT id FROM users WHERE username = :username', array (':username'=>$username))[0]['id'];
			DB::query('INSERT INTO user_login VALUES (\'\', :token, :user_id)', array (':token'=>sha1($token), ':user_id'=>$user_id));
			
			
			setcookie("ICS", $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);
			
			header('Location: index.php');
			
		}else{
			echo "Incorrect Password";
		}
		
	}else{
		echo "Incorrect Username";
	}
	
}

?>

<!--Login Section-->

<h1>User - Login</h1>
<form action="login.php" method="post">
	<span>Username:</span>
	<input type="text" name="username" placeholder="Username ..."/><p>
	<span>Password:</span>
	<input type="password" name="password" placeholder="Password ..."/>
	<p><input type="submit" name="login" value="LogIn"/>
</form>