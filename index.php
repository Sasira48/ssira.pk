<?php
require 'inc/header.php';
require 'inc/functions.php';

// ดึงสินค้าล่าสุด 20 ชิ้น
$products = get_products(20);
?>
<h1 class="text-2xl font-bold maroon-text mb-4">สินค้าทั้งหมด</h1>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
<?php foreach($products as $p): ?>
  <div class="bg-white p-3 rounded shadow">
    <img src="/SIRA_Cafe/assets/uploads/<?= e($p['image']?:'placeholder.png') ?>" alt="" class="h-40 w-full object-cover mb-2">
    <h3 class="font-semibold"><?= e($p['title']) ?></h3>
    <p class="text-sm text-gray-600"><?= e($p['shop_name']) ?></p>
    <div class="flex justify-between items-center mt-2">
      <div class="text-lg font-bold"><?= number_format($p['price'],2) ?> ฿</div>
      <div class="space-x-2">
        <button onclick="openQuickView(<?= $p['id'] ?>)" class="px-2 py-1 border rounded text-sm">Quick</button>
        <a href="product.php?id=<?= $p['id'] ?>" class="px-2 py-1 bg-maroon text-white rounded text-sm">View</a>
      </div>
    </div>
  </div>
<?php endforeach; ?>
</div>

<!-- Quick View Modal -->
<div id="quickViewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
  <div class="bg-white p-4 rounded w-96 relative">
    <span class="absolute top-1 right-2 cursor-pointer text-xl font-bold" onclick="closeQuickView()">✖</span>
    <div id="quickViewContent"></div>
  </div>
</div>

<script>
function openQuickView(id){
    fetch('api/product.php?id='+id)
    .then(res => res.json())
    .then(data => {
        if(!data.id) return alert("สินค้านี้ไม่มีอยู่");
        document.getElementById('quickViewContent').innerHTML = `
          <img src="/SIRA_Cafe/assets/uploads/${data.image || 'placeholder.png'}" class="h-40 w-full object-cover mb-2">
          <h3 class="font-semibold text-lg">${data.title}</h3>
          <p class="text-sm text-gray-500 mb-1">${data.shop_name}</p>
          <p class="text-gray-700 mb-2">${data.description || ''}</p>
          <p class="text-lg font-bold">${data.price} ฿</p>
          <a href="product.php?id=${data.id}" class="mt-2 inline-block px-3 py-1 bg-maroon text-white rounded text-sm">ดูรายละเอียด</a>
        `;
        document.getElementById('quickViewModal').classList.remove('hidden');
    });
}

function closeQuickView(){
    document.getElementById('quickViewModal').classList.add('hidden');
}
</script>

<?php require 'inc/footer.php'; ?>
