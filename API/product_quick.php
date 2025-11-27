<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/functions.php';

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT p.*, s.shop_name FROM products p JOIN sellers s ON p.seller_id=s.id WHERE p.id=? AND p.approved=1 LIMIT 1');
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$p) {
    http_response_code(404);
    echo '<div class="modal fixed inset-0 bg-black/50 flex items-center justify-center"><div class="bg-white p-4 rounded">ไม่พบสินค้า <button onclick="closeModal(this)">Close</button></div></div>';
    exit;
}

// render small HTML fragment
?>
<div class="modal fixed inset-0 bg-black/50 flex items-center justify-center z-50">
  <div class="bg-white rounded p-4 max-w-3xl w-full">
    <button class="float-right text-gray-500" onclick="closeModal(this)">✖</button>
    <div class="grid md:grid-cols-2 gap-4">
      <div><img src="/SIRA_Cafe/assets/uploads/<?= e($p['image']?:'placeholder.png') ?>" class="w-full h-64 object-cover"></div>
      <div>
        <h2 class="text-xl font-bold"><?= e($p['title']) ?></h2>
        <p class="text-sm text-gray-600"><?= e($p['shop_name']) ?></p>
        <p class="mt-2"><?= nl2br(e($p['description'])) ?></p>
        <div class="mt-4 text-2xl font-bold"><?= number_format($p['price'],2) ?> ฿</div>
        <form method="post" action="/SIRA_Cafe/cart_add.php" class="mt-3">
          <input type="hidden" name="product_id" value="<?= e($p['id']) ?>">
          <input type="number" name="qty" value="1" min="1" class="w-24 p-1 border rounded">
          <button class="px-3 py-1 bg-maroon text-white rounded">Add to cart</button>
        </form>
      </div>
    </div>
  </div>
</div>
