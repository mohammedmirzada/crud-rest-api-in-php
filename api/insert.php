<?php
header("Content-Type: application/json; charset=UTF-8");

include $_SERVER['DOCUMENT_ROOT'].'/db.php';

$db = new db();

$name = $_POST['name'];
$price = $_POST['price'];

if ($db->Insert('products', array('name' => $name, 'price' => $price))) {
	echo json_encode(array('data' => 'inserted'));
}else{
	echo json_encode(array('data' => 'error'));
}


?>