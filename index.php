<?php

include $_SERVER['DOCUMENT_ROOT'].'/db.php';
$db = new db();

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Shopping Sytem</title>
	<link rel="stylesheet" type="text/css" href="styles/style.css">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

<form class="_form" method="POST" action="/api/insert.php">
	<h1>Insert Data</h1>
	<input type="text" name="name" placeholder="Product name" class="input__">
	<input type="text" name="price" placeholder="Product price" class="input__">
	<input type="submit" name="submit" value="Add Product" class="button__">
</form>

<div class="_form">
	<h1>Get Data</h1>
	<div id="show_data"></div>
</div>

<div class="_form">
	<h1>Update Data</h1>
	<?php
	$data = '';
	foreach ($db->Get('products')->results() as $item) {
		$data .= '
		<form method="POST" action="/api/update.php">
		<input type="text" name="product_name" value="'.$item["name"].'">
		<input type="text" name="product_price" value="'.$item["price"].'">
		<input type="submit" name="Update" value="Update">
		<input type="hidden" name="id" value="'.$item["id"].'">
		</form>
		<form method="POST" action="/api/delete.php">
		<input type="submit" name="Delete" value="Delete">
		<input type="hidden" name="id" value="'.$item["id"].'">
		</form>
		';
	}
	echo $data;
	?>
</div>

<script type="text/javascript">
	$.ajax({
		url: "/api/get.php",
		data: "",
		beforeSend: function(r){

		},
		success: function(json){
			document.getElementById('show_data').innerHTML = json.data;
		},
		error: function(error){
			alert(error);
		}
	});
</script>

</body>
</html>