<?php

include('classes/DB.php');
include('./classes/Login.php');

if (Login::isLoggedIn()) {
	echo "Logged in!<p> User ID :".LogIn::isLoggedIn();
	
	if (LogIn::isAdmin()) {
		
		if (isset($_POST['add'])){
			
			$name = $_POST['cname'];
			
			if (!DB::query('SELECT * FROM companies WHERE name = :name', array(':name'=>$name))) {
			
				DB::query('INSERT INTO companies VALUES (\'\', :name)', array(':name'=>$name));
				
				echo "<hr>Company Added Successfully!";
			
			}else{
				echo "<hr>".$name." company already added!";
			}
			
		}
	
?>
	<hr>
	<h1>Add Company</h1>
	<form action="companies.php" method="post">
		<span>Company Name :</span>
		<input type="text" name="cname" placeholder="Company Name ..."><p>
		<input type="submit" name="add" value="Add">
	</form>
	
<?php } ?>
	
	<hr>
	
	<h1>List of Companies</h1>

<?php

	$company = DB::query('SELECT * FROM companies', array());
	$count = 1;
	
	foreach ($company as $c) {
		echo $count.". ".$c['name']."<br>";
		$count++;
	}

}else{
	header('Location: login.php');
}
?>