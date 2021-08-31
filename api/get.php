<?php
header("Content-Type: application/json; charset=UTF-8");

include $_SERVER['DOCUMENT_ROOT'].'/db.php';
$db = new db();

$data = '';
foreach ($db->Get('products')->results() as $item) {
	$data .= '<div class="data_style">'.$item['name'].'</div>';
}
echo json_encode(array('data' => $data));

?>