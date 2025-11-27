<?php
require_once 'inc/header.php';
require_once 'inc/config.php';
require_once 'inc/functions.php';
$user = current_user();
if (!$user) { header('Location: login.php'); exit; }

$stmt = $pdo->prepare('SELECT * FROM orders WHERE user_id=? ORDER BY created_at DESC');
$stmt->execute([$user['id']]); $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Order history</h2>
<?php foreach($orders as $o): ?>
  <div class="bg-white p-3 rounded mb-2">
    <div>Order #<?= e($o['id']) ?> - <?= e($o['status']) ?> - <?= e($o['created_at']) ?></div>
    <div>Total: <?= number_format($o['total'],2) ?> ฿</div>
    <a href="order_view.php?id=<?= e($o['id']) ?>" class="text-blue-600">View details</a>
  </div>
<?php endforeach; ?>
<?php require_once 'inc/footer.php'; ?>
