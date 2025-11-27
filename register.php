<?php
require_once 'inc/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'user';

    if($name && $email && $password){
        // ตรวจสอบ email ซ้ำ
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email=? LIMIT 1');
        $stmt->execute([$email]);
        if($stmt->fetch()){
            $error = "Email นี้มีอยู่แล้ว";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)');
            $stmt->execute([$name,$email,$hash,$role]);
            header('Location: login.php');
            exit;
        }
    } else {
        $error = "กรุณากรอกข้อมูลให้ครบ";
    }
}

require_once 'inc/header.php';
?>
<h2>Register</h2>
<?php if(!empty($error)) echo "<p class='text-red-500'>$error</p>"; ?>
<form method="post" class="max-w-sm space-y-2">
  <input name="name" required class="w-full p-2 border rounded" placeholder="Name">
  <input name="email" type="email" required class="w-full p-2 border rounded" placeholder="Email">
  <input name="password" type="password" required class="w-full p-2 border rounded" placeholder="Password">
  <select name="role" class="w-full p-2 border rounded">
    <option value="user">User</option>
    <option value="customer">Customer</option>
    <option value="employee">Employee</option>
    <option value="seller">Seller</option>
    <!-- admin ต้องสร้างโดยตรงใน DB -->
  </select>
  <button class="px-3 py-1 bg-maroon text-white rounded">Register</button>
</form>
<?php require_once 'inc/footer.php'; ?>
