<?php

include('classes/DB.php');
include('classes/Login.php');

if (!Login::isLoggedIn()) {
	header('Location: login.php');
}

if (isset($_POST['confirm'])) {
	
	if (isset($_COOKIE['ICS'])) {
		DB::query('DELETE FROM user_login WHERE token = :token', array(':token'=>sha1($_COOKIE['ICS'])));
		setcookie('ICS', '1', time()-3600);
	}
	
	header('Location: login.php');
	
}

?>

<h1>Logout Your Account</h1>
<hr>
<?php
	echo "Logged in!<p> User ID :".LogIn::isLoggedIn();
?>
<hr>
<p>Are you sure you'd like to logout?</p>

<form action = "logout.php" method = "POST">
	<input type="submit" name="confirm" value="Confirm">
</form>