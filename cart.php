<?php
require_once 'inc/header.php';
require_once 'inc/config.php';
require_once 'inc/functions.php';
$user = current_user();
if (!$user) { header('Location: login.php'); exit; }

$stmt = $pdo->prepare('SELECT c.*, p.title, p.price FROM cart c JOIN products p ON c.product_id=p.id WHERE c.user_id=?');
$stmt->execute([$user['id']]); $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total = 0;
?>
<h2 class="text-2xl font-bold">Cart</h2>
<?php if(!$rows): ?><p>Cart is empty</p><?php else: ?>
<form method="post" action="checkout.php">
<table class="w-full"><thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Sub</th></tr></thead>
<tbody>
<?php foreach($rows as $r): $sub = $r['price']*$r['qty']; $total += $sub; ?>
<tr class="border-b"><td><?= e($r['title']) ?></td><td><?= number_format($r['price'],2) ?></td><td><?= e($r['qty']) ?></td><td><?= number_format($sub,2) ?></td></tr>
<?php endforeach; ?>
</tbody></table>
<div class="mt-4">Total: <?= number_format($total,2) ?> ฿</div>
<button class="mt-2 px-3 py-1 bg-maroon text-white rounded">Checkout</button>
</form>
<?php endif; ?>
<?php require_once 'inc/footer.php'; ?>
