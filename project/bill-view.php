<?php

include('classes/DB.php');
include('./classes/Login.php');

if (Login::isLoggedIn()) {
	
	echo "Logged in!<p> User ID :".LogIn::isLoggedIn()."<hr>";
	
	$id = $_GET['id'];
	
	echo "<h1>Bill Detail</h1>";
	
	$bill = DB::query('SELECT * FROM bills WHERE id = :id', array(':id'=>$id));
	echo "<h3>Bill ID - ".$bill[0]['id']."</h3>";
	
?>
	<h3>Item Details</h3>
	<table>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Category</th>
			<th>Company</th>
			<th>Quantity</th>
			<th>Amount</th>
		</tr>
<?php
	
	$items = DB::query('SELECT * FROM bill_item WHERE bill_id = :id', array(':id'=>$id));
	
	foreach($items as $i){
		$item_detail = DB::query('SELECT * FROM items WHERE id = :item_id', array(':item_id'=>$i['item_id']));
		$category = DB::query('SELECT name FROM categories WHERE id = :id', array(':id'=>$item_detail[0]['category_id']))[0]['name'];
		$company = DB::query('SELECT name FROM companies WHERE id = :id', array(':id'=>$item_detail[0]['company_id']))[0]['name'];
		echo "<tr>
		<td>".$item_detail[0]['id']."</td>
		<td>".$item_detail[0]['name']."</td>
		<td>".$category."</td>
		<td>".$company."</td>
		<td>".$i['quantity']."</td>
		<td>".$i['amount']."</td>
		</tr>";
	}
	
?>
	</table>
<?php
	$entered_by = DB::query('SELECT name FROM users WHERE id = :id', array(':id'=>$bill[0]['entered_by']))[0]['name'];
	$name = DB::query('SELECT name FROM customers WHERE id = :id', array(':id'=>$bill[0]['customer_id']))[0]['name'];
	echo "<h4>Total Amount : ".$bill[0]['amount']."</h4>
	<h4>Status : ".$bill[0]['status']."</h4>
	<h4>Bill Entered By ".$entered_by."</h4>
	<h4>Customer Name is ".$name."</h4>
	<h4></h4>";
	
}else{
	header('Location: login.php');
}

?>