<?php
header("Content-Type: application/json; charset=UTF-8");


include $_SERVER['DOCUMENT_ROOT'].'/db.php';

$db = new db();

$id = $_POST['id'];

$db->Delete('products', $id);

?>