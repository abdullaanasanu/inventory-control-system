<?php

include('classes/DB.php');
include('./classes/Login.php');

if (Login::isLoggedIn()) {
	
	echo "Logged in!<p> User ID :".LogIn::isLoggedIn();
	
	if (LogIn::isAdmin()) {
		
		$total = 10;
		
		if (isset($_POST['increase'])){
			
			if ($_POST['no_items'] <= 20) {
				
				$total = $total + $_POST['no_items'];
				echo "<hr>Number of items updated to ".$total;
				
			}else{
				echo "<hr>We can't accept this much number of items";
			}
			
		}
		
		if (isset($_POST['add'])){
			
			do{
				$min = 1;
				$max = 9999999999;
				$id = rand ($min, $max);
			}while(DB::query('SELECT id FROM bills WHERE id = :id', array(':id'=>$id)));
			
			if (!DB::query('SELECT id FROM customers WHERE name = :name', array(':name'=>$_POST['cus_name']))){
				
				do{
					$min = 1;
					$max = 9999999999;
					$cus_id = rand ($min, $max);
				}while(DB::query('SELECT id FROM customers WHERE id = :id', array(':id'=>$id)));
				
				DB::query('INSERT INTO customers VALUES (:id, :name, :place, :email, :phone_no)', array(':id'=>$cus_id, ':name'=>$_POST['cus_name'], ':place'=>$_POST['cus_place'], ':email'=>$_POST['cus_email'], ':phone_no'=>$_POST['cus_phone']));
			}else{
				$cus_id = DB::query('SELECT id FROM customers WHERE name = :name', array(':name'=>$_POST['cus_name']))[0]['id'];
			}
			
			DB::query('INSERT INTO bills VALUES (:id, :number, :amount, :status, :entered_by, :cus_id)', array(':id'=>$id, 'number'=>0, ':amount'=>0, ':status'=>$_POST['status'], ':entered_by'=>LogIn::isLoggedIn(), ':cus_id'=>$cus_id));
			$total_amount = 0; 
			$num_of_items = 0;
			
			for ($j=1;$total > $j; $j++){
				
				if ($_POST['n'.$j] != "") {
					
					if (DB::query('SELECT id FROM items WHERE name = :name OR id = :id', array(':name'=>$_POST['n'.$j], ':id'=>$_POST['n'.$j]))){
					
						$item_id = DB::query('SELECT id FROM items WHERE name = :name OR id = :id', array(':name'=>$_POST['n'.$j], ':id'=>$_POST['n'.$j]))[0]['id'];
						$price = DB::query('SELECT value FROM items WHERE id = :id', array(':id'=>$item_id))[0]['value'];
						$stock = DB::query('SELECT stock FROM items WHERE id = :id', array(':id'=>$item_id))[0]['stock'];
						$quantity = $_POST['q'.$j];
						
						if ($stock >= $quantity) {
							
							$stock = $stock - $quantity;
							$item_amount = $price * $quantity;
							$total_amount = $total_amount + $item_amount;
							$num_of_items++;
							
							DB::query('UPDATE items SET stock = :stock WHERE id = :id', array(':stock'=>$stock,':id'=>$item_id));
							
							DB::query('INSERT INTO bill_item VALUES (\'\', :id, :item_id, :quantity, :amount)', array(':id'=>$id, ':item_id'=>$item_id, ':quantity'=>$quantity, ':amount'=>$item_amount));
							
						}else{
							echo "<br>".$_POST['n'.$j]." item don't have enough number of stocks!";
						}
					
					}else{
						echo "<br>".$_POST['n'.$j]." item doesn't exist!";
					}
					
				}
				
			}
			
			DB::query('UPDATE bills SET amount = :amount, num_of_items = :num_of_items WHERE id = :id', array(':amount'=>$total_amount, ':id'=>$id, ':num_of_items'=>$num_of_items));
			
		}
		
?>	<hr>
	<h1>Add Bill</h1>
	<form action="add-bill.php" method="post">
		<h3>Customer Details :</h3>
		<span>Name :</span>
		<input type="text" name="cus_name" placeholder="Customer Name ...">
		<span>Place :</span>
		<input type="text" name="cus_place" placeholder="Customer Place ...">
		<span>E-Mail :</span>
		<input type="email" name="cus_email" placeholder="Customer E-Mail ...">
		<span>Phone Number :</span>
		<input type="text" name="cus_phone" placeholder="Customer Phone Number ...">
		<h3>Items Details :</h3>
		<table>
			<tr>
				<th>Number</th>
				<th>Item Name</th>
				<th>Quantity</th>
				<th>Amount</th>
			</tr>
		
<?php

		for ($i=0;$i<$total;$i++) {
			$n = $i + 1;
			echo "
			<tr>
				<td>".$n.".</td>
				<td><input type='text' name='n".$n."' placeholder='Item Name ...'></td>
				<td><input type='number' name='q".$n."' placeholder='Quantity ...'></td>
				<td><center>-</center></td>
			</tr>
			";
		}

?>
			<tr>
				<td></td>
				<td></td>
				<td>Total Amount :</td>
				<td><center>-</center></td>
			</tr>
		</table>
		Bill Status :
		<select name="status" >
			<option value="paid">Paid</option>
			<option value="pending">Pending</option>
		</select>
		<p>
		<input type="submit" name="add" value="Add Bill">		
	</form>
	<form action="add-bill.php" method="post">
		<span><em>Add more number of Items : </em></span>
		<input type="number" name="no_items">
		<input type="submit" name="increase" value="Update">
	</form>
<?php
	}else{
		header('Location: bills.php');
	}
}else{
	header('Location: login.php');
}

?>