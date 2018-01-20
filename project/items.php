<?php

include('classes/DB.php');
include('./classes/Login.php');

if (Login::isLoggedIn()) {
	echo "Logged in!<p> User ID :".LogIn::isLoggedIn();
	
?>
	<hr>
	<a  href="categories.php"><button>Categories</button></a>
	<a  href="companies.php"><button>Companies</button></a>
	
<?php 	if (LogIn::isAdmin()) { 
			
			if (isset($_POST['add'])){
				
				$name = $_POST['iname'];
				$companyId = $_POST['cname'];
				$categoryId = $_POST['ctypename'];
				$value = $_POST['value'];
				
				DB::query('INSERT INTO items VALUES (\'\', :name, 0, :categoryId, :companyId, :value)', array(':name'=>$name, ':categoryId'=>$categoryId, ':companyId'=>$companyId, ':value'=>$value));
				
				echo "<hr>".$name." item added successfully!";
				
			}

?>
	
	<hr>
	
<!-- Add New Items -->

<h1>Add Item</h1>
<form action="items.php" method="post">
	<span>Name : </span>
	<input type="text" name="iname" placeholder="Item Name ..."/><p>
	<span>Price : </span>
	<input type="number" name="value" placeholder="Item Price ..."/><p>
	<span>Company : </span>
	<select name="cname">
<?php
	
		$company = DB::query('SELECT * FROM companies', array());
		
		foreach ($company as $c) {
			echo "<option value='".$c['id']."'>".$c['name']."</option>";
		}
?>
	</select>
	<span> Category : </span>
	<select name="ctypename">
<?php
	
		$category = DB::query('SELECT * FROM categories', array());
		
		foreach ($category as $ca) {
			echo "<option value='".$ca['id']."'>".$ca['name']."</option>";
		}
?>
	</select><p>
	<input type="submit" name="add" value="Add">
</form>

<?php } ?>
<hr>
<h1>Search Items</h1>
<form action="items.php" method="post">
	<span>Name :</span>
	<input type="text" name="sname" placeholder="Name ...">
	<span>Price :</span>
	<input type="text" name="sprice" placeholder="Price ...">
	<span>Company :</span>
	<select name="scompany">
<?php 
		foreach ($company as $c) {
			echo "<option value='".$c['id']."'>".$c['name']."</option>";
		}
?>
	</select>
	<span>Category :</span>
	<select name="scategory">
	
<?php 
		foreach ($category as $ca) {
			echo "<option value='".$ca['id']."'>".$ca['name']."</option>";
		}
?>
	
	</select>
	<input type="submit" name="search" value="search">
</form>

<?php

	if(isset($_POST['search'])){
		
		$sname = $_POST['sname'];
		$sprice = $_POST['sprice'];
		$company_id = $_POST['scompany'];
		$category_id = $_POST['scategory'];
		
		if(DB::query('SELECT * FROM items WHERE name LIKE :name OR value = :price OR company_id = :company_id OR category_id = :category_id', array(':name'=>$sname, ':price'=>$sprice, ':company_id'=>$company_id, ':category_id'=>$category_id))){
			
			$items = DB::query('SELECT * FROM items WHERE name LIKE :name OR value = :price OR company_id = :company_id OR category_id = :category_id', array(':name'=>$sname, ':price'=>$sprice, ':company_id'=>$company_id, ':category_id'=>$category_id));
			
			echo "<h1>Search Result</h1>";
			
			foreach ($items as $i) {
					$company = DB::query('SELECT name FROM companies WHERE id = :id', array(':id'=>$i['company_id']))[0]['name'];
					$category = DB::query('SELECT name FROM categories WHERE id = :id', array(':id'=>$i['category_id']))[0]['name'];
					echo "<strong>".$i['name']."</strong><br>
					Price : Rs. ".$i['value']."<br>
					Stocks : ".$i['stock']."<br>
					Company : ".$company."<br>
					Category : ".$category."<p>
					";
				}
			
		}
		
	}else{

?>

<hr>
<h1>List of Items</h1>

<?php
		$items = DB::query('SELECT * FROM items', array());
		$count = 1;
		
		foreach ($items as $i) {
			$company = DB::query('SELECT name FROM companies WHERE id = :id', array(':id'=>$i['company_id']))[0]['name'];
			$category = DB::query('SELECT name FROM categories WHERE id = :id', array(':id'=>$i['category_id']))[0]['name'];
			echo $count.". <strong>".$i['name']."</strong><br>
			Price : Rs. ".$i['value']."<br>
			Stocks : ".$i['stock']."<br>
			Company : ".$company."<br>
			Category : ".$category."<p>
			";
			$count++;
		}
		
	}
?>


<?php
}else{
	header('Location: login.php');
}
?>
