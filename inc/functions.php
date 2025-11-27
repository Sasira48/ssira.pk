<?php
require_once __DIR__ . '/config.php';

if (!function_exists('current_user')) {
    function current_user() {
        global $pdo;
        if (!isset($_SESSION['user_id'])) return null;

        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

if (!function_exists('require_role')) {
    function require_role($roles = []) {
        $user = current_user();
        if (!$user || !in_array($user['role'], $roles)) {
            header("Location: /SIRA_Cafe/no_permission.php");
            exit;
        }
    }
}

if (!function_exists('e')) {
    function e($string){
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('get_products')) {
    function get_products($limit = 20){
        global $pdo;
        $stmt = $pdo->prepare("SELECT p.*, s.shop_name 
                               FROM products p 
                               LEFT JOIN sellers s ON p.seller_id = s.id 
                               WHERE p.approved = 1 
                               ORDER BY p.created_at DESC 
                               LIMIT ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

if (!function_exists('get_product')) {
    function get_product($id){
        global $pdo;
        $stmt = $pdo->prepare("SELECT p.*, s.shop_name 
                               FROM products p 
                               LEFT JOIN sellers s ON p.seller_id = s.id 
                               WHERE p.id = ? AND p.approved = 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
