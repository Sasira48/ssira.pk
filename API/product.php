<?php
require '../inc/config.php';
require '../inc/functions.php';

$id = intval($_GET['id'] ?? 0);
$product = get_product($id);

header('Content-Type: application/json');
echo json_encode($product ?: []);
