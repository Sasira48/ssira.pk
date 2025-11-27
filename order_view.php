<?php
require_once 'inc/header.php';
require_once 'inc/config.php';
require_once 'inc/functions.php';
$user = current_user();
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM orders WHERE id=? AND user_id=? LIMIT 1'); $stmt->execute([$id,$user['id']]); $order = $stmt->fetch();
if (!$order) { echo 'Not found'; require_once 'inc/footer.php'; exit; }
$items = $pdo->prepare('SELECT oi.*, p.title FROM order_items oi JOIN products p ON oi.product_id=p.id WHERE oi.order_id=?'); $items->execute([$id]); $items = $items->fetchAll();
?>
<h2>Order #<?= e($order['id']) ?></h2>
<?php foreach($items as $it): ?>
  <div><?= e($it['title']) ?> x<?= e($it['qty']) ?> - <?= number_format($it['price'],2) ?> ฿</div>
<?php endforeach; ?>
<?php require_once 'inc/footer.php'; ?>
