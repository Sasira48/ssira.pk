<?php
require_once 'inc/header.php';
$id = intval($_GET['id'] ?? 0);
?>
<h2>Order placed</h2>
<p>Order ID: <?= e($id) ?></p>
<?php require_once 'inc/footer.php'; ?>
