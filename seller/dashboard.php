<?php
require_once __DIR__ . '/../inc/header.php';
$user = current_user(); if(!$user || $user['role']!=='seller'){ echo '<p>No access</p>'; require '../inc/footer.php'; exit; }
// find seller id
$stmt=$pdo->prepare('SELECT * FROM sellers WHERE user_id=?'); $stmt->execute([$user['id']]); $seller = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$seller){ echo '<p>Please create seller profile first.</p>'; require '../inc/footer.php'; exit; }
// products
$stmt = $pdo->prepare('SELECT * FROM products WHERE seller_id=?'); $stmt->execute([$seller['id']]); $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
// orders for this seller (from order_items)
$stmt = $pdo->prepare('SELECT oi.*, o.status, o.created_at FROM order_items oi JOIN orders o ON oi.order_id=o.id WHERE oi.seller_id=? ORDER BY o.created_at DESC');
$stmt->execute([$seller['id']]); $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h2 class="text-xl font-bold">Seller Dashboard - <?= e($seller['shop_name']) ?></h2>
<a href="product_add.php" class="px-2 py-1 bg-maroon text-white rounded">Add product</a>
<h3 class="mt-4 font-semibold">Your Products</h3>
<div class="grid grid-cols-1 md:grid-cols-3 gap-3">
<?php foreach($products as $p): ?>
  <div class="bg-white p-2 rounded shadow">
    <img src="/SIRA_Cafe/assets/uploads/<?= e($p['image']?:'placeholder.png') ?>" class="h-32 w-full object-cover mb-2">
    <div class="font-semibold"><?= e($p['title']) ?></div>
    <div><?= number_format($p['price'],2) ?> ฿</div>
    <div class="text-sm">Approved: <?= $p['approved']? 'Yes':'No' ?></div>
    <a href="product_edit.php?id=<?= $p['id'] ?>" class="text-sm text-blue-600">Edit</a>
  </div>
<?php endforeach; ?>
</div>
<h3 class="mt-6 font-semibold">Orders</h3>
<?php foreach($orders as $o): ?>
  <div class="bg-white p-2 rounded mb-2">Order #<?= e($o['order_id']) ?> - <?= e($o['status']) ?> - <?= e($o['created_at']) ?> - Qty <?= e($o['qty']) ?></div>
<?php endforeach; ?>
<?php require_once __DIR__ . '/../inc/footer.php'; ?>
