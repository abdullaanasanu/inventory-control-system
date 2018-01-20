<?php

include('classes/DB.php');
include('./classes/Login.php');

if (Login::isLoggedIn()) {
	
	echo "Logged in!<p> User ID :".LogIn::isLoggedIn();
	
	if (LogIn::isAdmin()) {
		
?>	<hr>
	<a href="add-bill.php"><button>Add New Bill</button></a>
<?php
	}
	
?>
	<hr>
	<h1>Search Bills</h1>
	<form action="bills.php" method="post">
		<span>Bill ID : </span>
		<input type="text" name="id" placeholder="Bill ID ...">
		<span>Customer Name : </span>
		<input type="text" name="cus_name" placeholder="Customer Name ...">
		<span>Bill Status :</span>
		<select name="status">
			<option value="none">None</option>
			<option value="paid">Paid</option>
			<option value="pending">Pending</option>
		</select><p>
		<input type="submit" name="search" value="Search">
	</form>
	<hr>
	
<?php
	if(isset($_POST['search'])){
		$id = $_POST['id'];
		$cus_name = $_POST['cus_name'];
		$status = $_POST['status'];
		if ($cus_name != ""){
			$cus_id = DB::query('SELECT id FROM customers WHERE name LIKE :cus_name', array(':cus_name'=>$cus_name))[0]['id'];
		}else{
			$cus_id = null;
		}
?>
		<h1>Search Result</h1>
		
<?php
		if(DB::query('SELECT * FROM bills WHERE id = :id OR customer_id = :cus_id OR status = :status', array(':id'=>$id, ':cus_id'=>$cus_id, ':status'=>$status))){
			
			$bills = DB::query('SELECT * FROM bills WHERE id = :id OR customer_id = :cus_id OR status = :status', array(':id'=>$id, ':cus_id'=>$cus_id, ':status'=>$status));
			
			foreach ($bills as $b) {
				$name = DB::query('SELECT name FROM customers WHERE id = :id', array(':id'=>$b['customer_id']))[0]['name'];
				echo "<a href='bill-view.php?id=".$b['id']."'>Bill ID - ".$b['id']."</a><br>
				Number of Items = ".$b['num_of_items']."<br>
				Amount = ".$b['amount']."<br>
				Customer Name : ".$name."<hr>";
			}
			
		}else{
			echo "There is no Bill founded for your request";
		}
		
	}else{
?>
		<h1>Latest Bills</h1>
<?php

		$bills = DB::query('SELECT * FROM bills', array());
		
		foreach ($bills as $b) {
			$name = DB::query('SELECT name FROM customers WHERE id = :id', array(':id'=>$b['customer_id']))[0]['name'];
			echo "<a href='bill-view.php?id=".$b['id']."'>Bill ID - ".$b['id']."</a><br>
			Number of Items = ".$b['num_of_items']."<br>
			Amount = ".$b['amount']."<br>
			Customer Name : ".$name."<hr>";
		}
	}
}else{
	header('Location: login.php');
}

?>