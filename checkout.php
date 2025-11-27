<?php
require_once 'inc/config.php';
require_once 'inc/functions.php';
$user = current_user();
if (!$user) { header('Location: login.php'); exit; }

$stmt = $pdo->prepare('SELECT c.*, p.price, p.seller_id FROM cart c JOIN products p ON c.product_id=p.id WHERE c.user_id=?');
$stmt->execute([$user['id']]); $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (!$items) { header('Location: cart.php'); exit; }

$total = 0; foreach($items as $it) $total += $it['price']*$it['qty'];

$pdo->beginTransaction();
$pdo->prepare('INSERT INTO orders (user_id,total,status) VALUES (?,?,?)')->execute([$user['id'],$total,'pending']);
$order_id = $pdo->lastInsertId();

$ins = $pdo->prepare('INSERT INTO order_items (order_id,product_id,seller_id,price,qty) VALUES (?,?,?,?,?)');
foreach($items as $it) {
    $ins->execute([$order_id,$it['product_id'],$it['seller_id'],$it['price'],$it['qty']]);
}
$pdo->prepare('DELETE FROM cart WHERE user_id=?')->execute([$user['id']]);
$pdo->commit();

header('Location: order_success.php?id='.$order_id);
exit;
