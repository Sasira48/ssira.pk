<?php
require_once __DIR__ . '/../inc/header.php';
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/functions.php';

$user = current_user();
if (!$user || $user['role'] !== 'seller') { echo 'No access'; require_once __DIR__ . '/../inc/footer.php'; exit; }

$stmt = $pdo->prepare('SELECT * FROM sellers WHERE user_id=? LIMIT 1');
$stmt->execute([$user['id']]); $seller = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$seller) { echo 'Create seller profile first'; require_once __DIR__ . '/../inc/footer.php'; exit; }

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM products WHERE id=? AND seller_id=? LIMIT 1');
$stmt->execute([$id,$seller['id']]); $p = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$p) { echo 'Not found'; require_once __DIR__ . '/../inc/footer.php'; exit; }

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $title = trim($_POST['title']); $desc = trim($_POST['description']);
    $price = floatval($_POST['price']); $stock = intval($_POST['stock']);
    $image_name = $p['image'];

    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $newname = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $dest = __DIR__ . '/../assets/uploads/' . $newname;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
            // optional: unlink old file
            if ($p['image'] && file_exists(__DIR__ . '/../assets/uploads/' . $p['image'])) {
                @unlink(__DIR__ . '/../assets/uploads/' . $p['image']);
            }
            $image_name = $newname;
        }
    }

    $upd = $pdo->prepare('UPDATE products SET title=?,description=?,price=?,stock=?,image=?,approved=0 WHERE id=? AND seller_id=?');
    $upd->execute([$title,$desc,$price,$stock,$image_name,$id,$seller['id']]);
    $success = 'อัปเดตสินค้าเรียบร้อย (รออนุมัติ)';
}

?>
<h2 class="text-2xl font-bold">Edit Product</h2>
<?php if(!empty($success)) echo '<div class="p-2 bg-green-100">'.$success.'</div>'; ?>
<form method="post" enctype="multipart/form-data" class="space-y-2 max-w-lg">
  <input name="title" required value="<?= e($p['title']) ?>" class="w-full p-2 border rounded">
  <textarea name="description" class="w-full p-2 border rounded"><?= e($p['description']) ?></textarea>
  <input name="price" type="number" step="0.01" value="<?= e($p['price']) ?>" class="w-full p-2 border rounded">
  <input name="stock" type="number" value="<?= e($p['stock']) ?>" class="w-full p-2 border rounded">
  <div>Current image:</div>
  <?php if($p['image']): ?><img src="/SIRA_Cafe/assets/uploads/<?= e($p['image']) ?>" class="h-32 mb-2"><?php endif; ?>
  <input type="file" name="image" accept="image/*" class="w-full">
  <button class="px-3 py-1 bg-maroon text-white rounded">Save</button>
</form>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>
