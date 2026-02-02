<?php
session_start();

require_once __DIR__ . '/../config/database.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    die('Eksik bilgi');
}

$sql = "SELECT id, password_hash FROM admins WHERE username = :username LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute(['username' => $username]);
$admin = $stmt->fetch();

if (!$admin) {
    die('Kullanıcı bulunamadı');
}

if (!password_verify($password, $admin['password_hash'])) {
    die('Şifre yanlış');
}

// LOGIN BAŞARILI
$_SESSION['admin_id'] = $admin['id'];

header('Location: /alintilar/admin/dashboard.php');
exit;
