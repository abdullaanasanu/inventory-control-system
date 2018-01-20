<?php

include('./classes/Login.php');
include('classes/DB.php');

if (Login::isLoggedIn()) {
	echo "Logged in!<p> User ID :".LogIn::isLoggedIn();
	
?>
	<hr>
	<a  href="items.php"><button>Items</button></a>
	<a  href="categories.php"><button>Categories</button></a>
	<a  href="companies.php"><button>Companies</button></a>
	<a  href="bills.php"><button>Bills</button></a>
<?php
	
}else{
	header('Location: login.php');
}

?>