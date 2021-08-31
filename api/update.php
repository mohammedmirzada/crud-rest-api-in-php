<?php
header("Content-Type: application/json; charset=UTF-8");


include $_SERVER['DOCUMENT_ROOT'].'/db.php';

$db = new db();

$product_name = $_POST['product_name'];
$product_price = $_POST['product_price'];
$id = $_POST['id'];

$db->Update('products', array('name' => $product_name, 'price' => $product_price), $id);

?>