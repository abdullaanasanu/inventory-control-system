<?php

include('classes/DB.php');
include('./classes/Login.php');

if (Login::isLoggedIn()) {
	echo "Logged in!<p> User ID :".LogIn::isLoggedIn();
	
	if (LogIn::isAdmin()){
		
		if (isset($_POST['add'])){
			
			$id = $_GET['i'];
			$cstock = DB::query('SELECT stock FROM items WHERE id = :id', array(':id'=>$id))[0]['stock'];
			$stock = $_POST['stock'] + $cstock;
			$name = DB::query('SELECT name FROM items WHERE id = :id', array(':id'=>$id))[0]['name'];
			
			DB::query('UPDATE items SET stock = :stock WHERE id = :id', array(':id'=>$id, ':stock'=>$stock));
			
			echo "<hr><strong>".$name."</strong> item's stocks added successfully!";
			
		}
		
	}
	
?>	<hr>
	<h1>Stocks</h1>
	
<?php
	$items = DB::query('SELECT * FROM items', array());
	$count = 1;
	
	foreach ($items as $i) {
		$company = DB::query('SELECT name FROM companies WHERE id = :id', array(':id'=>$i['company_id']))[0]['name'];
		$category = DB::query('SELECT name FROM categories WHERE id = :id', array(':id'=>$i['category_id']))[0]['name'];
		echo $count.". <strong>".$i['name']."</strong><br>
		Current Stock : ".$i['stock']."<br>
		Company : ".$company."<br>
		Category : ".$category."<br>";
		if (LogIn::isAdmin()){
			echo "<form action='stocks.php?i=".$i['id']."' method='post'>
			<input type='number' name='stock'><br>
			<input type='submit' name='add' value='Add More Stock'>
			</form><p>
			";
		}else{
			echo "<p>";
		}
		$count++;
	}
	
}else{
	header('Location: login.php');
}

?>