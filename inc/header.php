<?php
// เริ่ม session ให้ทุกหน้า
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

$user = current_user();
?>
<!doctype html>
<html lang="th">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SIRA Cafe</title>
  <link rel="icon" href="/SIRA_Cafe/assets/favicon.ico">
  <!-- Google Kanit -->
  <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600;700&display=swap" rel="stylesheet">
  <!-- Tailwind CDN (for dev) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body{font-family:'Kanit',sans-serif}
    .maroon{background-color:#800000}
    .maroon-text{color:#800000}
  </style>
</head>
<body class="bg-gray-50 min-h-screen">

<header class="maroon text-white p-4 shadow-md">
  <div class="container mx-auto flex justify-between items-center">
    <div class="flex items-center gap-3">
      <img src="/SIRA_Cafe/assets/logo.png" alt="logo" class="h-10 w-10 rounded-full bg-white p-1">
      <a href="/SIRA_Cafe/" class="text-xl font-bold">SIRA Cafe</a>
    </div>
    <nav class="space-x-4">
      <a href="/SIRA_Cafe/" class="hover:underline">Home</a>

      <?php if (!$user): ?>
        <a href="/SIRA_Cafe/register.php">Register</a>
        <a href="/SIRA_Cafe/login.php">Login</a>

      <?php else: ?>
        <?php if ($user['role'] == 'seller'): ?>
            <a href="/SIRA_Cafe/seller/dashboard.php">Seller</a>
        <?php endif; ?>

        <?php if ($user['role'] == 'admin'): ?>
            <a href="/SIRA_Cafe/admin/dashboard.php">Admin</a>
        <?php endif; ?>

        <a href="/SIRA_Cafe/cart.php">Cart</a>
        <a href="/SIRA_Cafe/logout.php">Logout</a>

      <?php endif; ?>
    </nav>
  </div>
</header>

<main class="container mx-auto p-4">
