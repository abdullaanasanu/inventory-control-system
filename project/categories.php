<?php

include('classes/DB.php');
include('./classes/Login.php');

if (Login::isLoggedIn()) {
	echo "Logged in!<p> User ID :".LogIn::isLoggedIn();
	
	if (LogIn::isAdmin()) {
		
		if (isset($_POST['add'])){
			
			$name = $_POST['cname'];
			
			if (!DB::query('SELECT * FROM categories WHERE name = :name', array(':name'=>$name))) {
			
				DB::query('INSERT INTO categories VALUES (\'\', :name)', array(':name'=>$name));
				
				echo "<hr>category Added Successfully!";
			
			}else{
				echo "<hr>".$name." category already added!";
			}
			
		}
	
?>
	<hr>
	<h1>Add Category</h1>
	<form action="categories.php" method="post">
		<span>Category Name :</span>
		<input type="text" name="cname" placeholder="Category Name ..."><p>
		<input type="submit" name="add" value="Add">
	</form>
	
<?php } ?>
	
	<hr>
	
	<h1>List of categories</h1>

<?php

	$category = DB::query('SELECT * FROM categories', array());
	$count = 1;
	
	foreach ($category as $c) {
		echo $count.". ".$c['name']."<br>";
		$count++;
	}

}else{
	header('Location: login.php');
}
?>