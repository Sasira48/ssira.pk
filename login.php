<?php
require_once 'inc/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // เปลี่ยนหน้า redirect ตาม role
        switch ($user['role']) {
            case 'admin':
                header('Location: admin/dashboard.php'); break;
            case 'seller':
                header('Location: seller/dashboard.php'); break;
            default:
                header('Location: index.php'); break;
        }
        exit;
    } else {
        $error = "Email หรือรหัสผ่านไม่ถูกต้อง";
    }
}
require_once 'inc/header.php';
?>
<h2>Login</h2>
<?php if(!empty($error)) echo "<p class='text-red-500'>$error</p>"; ?>
<form method="post" class="max-w-sm space-y-2">
  <input name="email" type="email" required class="w-full p-2 border rounded" placeholder="Email">
  <input name="password" type="password" required class="w-full p-2 border rounded" placeholder="Password">
  <button class="px-3 py-1 bg-maroon text-white rounded">Login</button>
</form>
<?php require_once 'inc/footer.php'; ?>
