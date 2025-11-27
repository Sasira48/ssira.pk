<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/header.php';

$user = current_user();
if (!$user || $user['role'] !== 'admin') { echo 'No access'; require_once __DIR__ . '/../inc/footer.php'; exit; }

// counts
$total_users = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$total_products = $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$pending_products = $pdo->query('SELECT COUNT(*) FROM products WHERE approved=0')->fetchColumn();

// sample data for chart: products per seller
$rows = $pdo->query('SELECT s.shop_name, COUNT(p.id) AS cnt FROM sellers s LEFT JOIN products p ON p.seller_id=s.id GROUP BY s.id ORDER BY cnt DESC LIMIT 10')->fetchAll(PDO::FETCH_ASSOC);
$labels = []; $data = [];
foreach($rows as $r){ $labels[] = $r['shop_name']; $data[] = (int)$r['cnt']; }
?>
<h2 class="text-2xl font-bold mb-4">Admin Dashboard</h2>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
  <div class="bg-white p-4 rounded shadow">
    <div class="text-sm text-gray-500">Total Users</div>
    <div class="text-2xl font-bold"><?= e($total_users) ?></div>
  </div>
  <div class="bg-white p-4 rounded shadow">
    <div class="text-sm text-gray-500">Total Products</div>
    <div class="text-2xl font-bold"><?= e($total_products) ?></div>
  </div>
  <div class="bg-white p-4 rounded shadow">
    <div class="text-sm text-gray-500">Pending Approvals</div>
    <div class="text-2xl font-bold"><?= e($pending_products) ?></div>
  </div>
</div>

<div class="mt-6 bg-white p-4 rounded shadow">
  <h3 class="font-semibold mb-2">Top sellers (by product count)</h3>
  <canvas id="chartSellers" height="100"></canvas>
</div>

<!-- Table pending (reuse earlier) -->
<h3 class="mt-6 font-semibold">Products pending</h3>
<?php
$products = $pdo->query('SELECT p.*, s.shop_name FROM products p JOIN sellers s ON p.seller_id=s.id WHERE p.approved=0')->fetchAll(PDO::FETCH_ASSOC);
foreach($products as $p):
?>
  <div class="bg-white p-3 rounded mb-2 flex gap-4">
    <img src="/SIRA_Cafe/assets/uploads/<?= e($p['image']?:'placeholder.png') ?>" class="h-20 w-20 object-cover">
    <div>
      <div class="font-semibold"><?= e($p['title']) ?></div>
      <div class="text-sm text-gray-600">Shop: <?= e($p['shop_name']) ?></div>
      <div class="mt-2">
        <a href="product_approve.php?id=<?= $p['id'] ?>&act=approve" class="px-2 py-1 bg-green-600 text-white rounded">Approve</a>
        <a href="product_approve.php?id=<?= $p['id'] ?>&act=reject" class="px-2 py-1 bg-red-600 text-white rounded">Reject</a>
      </div>
    </div>
  </div>
<?php endforeach; ?>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('chartSellers').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: <?= json_encode($labels) ?>,
      datasets: [{
        label: 'Products',
        data: <?= json_encode($data) ?>,
        backgroundColor: 'rgba(128,0,0,0.8)'
      }]
    },
    options: { responsive:true, maintainAspectRatio:false }
  });
</script>
