<?php
require_once 'inc/config.php';
require_once 'inc/functions.php';
$user = current_user();
if (!$user) { header('Location: login.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') header('Location: index.php');

$product_id = intval($_POST['product_id']);
$qty = intval($_POST['qty']) ?: 1;

$stmt = $pdo->prepare('SELECT * FROM cart WHERE user_id=? AND product_id=?');
$stmt->execute([$user['id'],$product_id]);
$exists = $stmt->fetch(PDO::FETCH_ASSOC);

if ($exists) {
    $pdo->prepare('UPDATE cart SET qty=qty+? WHERE id=?')->execute([$qty,$exists['id']]);
} else {
    $pdo->prepare('INSERT INTO cart (user_id, product_id, qty) VALUES (?,?,?)')->execute([$user['id'],$product_id,$qty]);
}

header('Location: cart.php');
exit;
