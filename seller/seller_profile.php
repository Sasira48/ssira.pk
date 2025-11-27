<?php
require_once __DIR__ . '/../inc/header.php';
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/functions.php';

$user = current_user();
if (!$user || $user['role'] !== 'seller') {
    echo 'No access';
    require_once __DIR__ . '/../inc/footer.php';
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM sellers WHERE user_id=? LIMIT 1');
$stmt->execute([$user['id']]);
$seller = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shop_name = trim($_POST['shop_name']);
    $desc = trim($_POST['description']);
    if ($seller) {
        $up = $pdo->prepare('UPDATE sellers SET shop_name=?, description=? WHERE id=?');
        $up->execute([$shop_name, $desc, $seller['id']]);
    } else {
        $ins = $pdo->prepare('INSERT INTO sellers (user_id, shop_name, description, approved) VALUES (?,?,?,0)');
        $ins->execute([$user['id'], $shop_name, $desc]);
    }
    header('Location: dashboard.php');
    exit;
}
?>
<h2 class="text-2xl font-bold">Seller Profile</h2>
<form method="post" class="max-w-lg space-y-2">
  <input name="shop_name" required value="<?= e($seller['shop_name'] ?? '') ?>" class="w-full p-2 border rounded" placeholder="Shop name">
  <textarea name="description" class="w-full p-2 border rounded" placeholder="Description"><?= e($seller['description'] ?? '') ?></textarea>
  <button class="px-3 py-1 bg-maroon text-white rounded">Save</button>
</form>
<?php require_once __DIR__ . '/../inc/footer.php'; ?>
