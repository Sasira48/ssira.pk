<?php
require 'inc/header.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT p.*, s.shop_name FROM products p JOIN sellers s ON p.seller_id=s.id WHERE p.id=?');
$stmt->execute([$id]); $p = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$p){ echo '<p>ไม่พบสินค้า</p>'; require 'inc/footer.php'; exit; }
?>
<div class="bg-white p-4 rounded shadow">
  <div class="grid md:grid-cols-2 gap-4">
    <img src="/SIRA_Cafe/assets/uploads/<?= e($p['image']?:'placeholder.png') ?>" class="w-full h-96 object-cover">
    <div>
      <h1 class="text-2xl font-bold"><?= e($p['title']) ?></h1>
      <p class="text-sm text-gray-600"><?= e($p['shop_name']) ?></p>
      <p class="mt-3"><?= nl2br(e($p['description'])) ?></p>
      <div class="mt-4 text-3xl font-bold"><?= number_format($p['price'],2) ?> ฿</div>
      <form method="post" action="cart_add.php" class="mt-4">
        <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
        <input type="number" name="qty" value="1" min="1" class="w-28 p-1 border rounded">
        <button class="px-3 py-1 bg-maroon text-white rounded">Add to cart</button>
      </form>
    </div>
  </div>
</div>
<?php require 'inc/footer.php'; ?>
