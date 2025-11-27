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

// หา seller record
$stmt = $pdo->prepare('SELECT * FROM sellers WHERE user_id=? LIMIT 1');
$stmt->execute([$user['id']]);
$seller = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$seller) {
    echo '<div class="p-3 bg-yellow-100">กรุณาสร้างโปรไฟล์ร้านก่อน <a href="seller_profile.php" class="text-blue-600">Create profile</a></div>';
    require_once __DIR__ . '/../inc/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $image_name = null;

    // handle upload
    if (!empty($_FILES['image']['name'])) {
        $allowed = ['jpg','jpeg','png','webp','gif'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            $error = 'ไฟล์ไม่รองรับ';
        } else {
            $newname = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            $dest = __DIR__ . '/../assets/uploads/' . $newname;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $image_name = $newname;
            }
        }
    }

    if (!isset($error)) {
        $ins = $pdo->prepare('INSERT INTO products (seller_id,title,description,price,stock,image,approved) VALUES (?,?,?,?,?,?,0)');
        $ins->execute([$seller['id'],$title,$desc,$price,$stock,$image_name]);
        $success = 'เพิ่มสินค้าสำเร็จ รออนุมัติจากผู้ดูแล';
    }
}
?>
<h2 class="text-2xl font-bold">Add Product</h2>
<?php if(!empty($error)): ?><div class="p-2 bg-red-100"><?= e($error) ?></div><?php endif; ?>
<?php if(!empty($success)): ?><div class="p-2 bg-green-100"><?= e($success) ?></div><?php endif; ?>

<form method="post" enctype="multipart/form-data" class="space-y-2 max-w-lg">
  <input name="title" required placeholder="Title" class="w-full p-2 border rounded">
  <textarea name="description" placeholder="Description" class="w-full p-2 border rounded"></textarea>
  <input name="price" type="number" step="0.01" class="w-full p-2 border rounded" placeholder="Price">
  <input name="stock" type="number" class="w-full p-2 border rounded" placeholder="Stock">
  <input type="file" name="image" accept="image/*" class="w-full">
  <button class="px-3 py-1 bg-maroon text-white rounded">Add product</button>
</form>

<?php require_once __DIR__ . '/../inc/footer.php'; ?>
