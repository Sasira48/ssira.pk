<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/functions.php';
require_once __DIR__ . '/../inc/header.php';

$user = current_user();
if (!$user || $user['role'] !== 'admin') {
    echo '<div class="p-4 bg-red-100">No permission</div>';
    require_once __DIR__ . '/../inc/footer.php';
    exit;
}

$id = intval($_GET['id'] ?? 0);
$act = $_GET['act'] ?? '';

if (!$id || !in_array($act, ['approve','reject'])) {
    header('Location: dashboard.php');
    exit;
}

if ($act === 'approve') {
    $stmt = $pdo->prepare('UPDATE products SET approved=1 WHERE id=?');
    $stmt->execute([$id]);
} else {
    // reject -> delete product (or set approved=2 for rejected if want keep)
    $stmt = $pdo->prepare('DELETE FROM products WHERE id=?');
    $stmt->execute([$id]);
}

header('Location: dashboard.php');
exit;
