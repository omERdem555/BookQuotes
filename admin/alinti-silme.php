<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Geçersiz istek');
}

$quoteId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($quoteId <= 0) {
    die('Geçersiz alıntı ID');
}

$stmt = $pdo->prepare("DELETE FROM quotes WHERE id = :id");
$ok = $stmt->execute(['id' => $quoteId]);

if (!$ok) {
    die('Alıntı silinemedi');
}

header('Location: /alintilar/admin/alinti-listele.php?deleted=1');
exit;
