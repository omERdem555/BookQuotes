<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';

/* Sadece POST */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Geçersiz istek');
}

$bookId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($bookId <= 0) {
    die('Geçersiz kitap ID');
}

/* Kitap var mı? */
$stmt = $pdo->prepare("SELECT id, title FROM books WHERE id = :id LIMIT 1");
$stmt->execute(['id' => $bookId]);
$book = $stmt->fetch();

if (!$book) {
    die('Silinmek istenen kitap bulunamadı');
}

/* Alıntı sayısını öğren (bilgilendirme amaçlı) */
$countStmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM quotes 
    WHERE book_id = :id
");
$countStmt->execute(['id' => $bookId]);
$quoteCount = (int)$countStmt->fetchColumn();

/*
    Burada PHP tarafında alıntı silmiyoruz.
    FK + ON DELETE CASCADE işi veritabanına bırakıyoruz.
*/
$del = $pdo->prepare("DELETE FROM books WHERE id = :id");
$ok = $del->execute(['id' => $bookId]);

if (!$ok) {
    die('Kitap silinirken hata oluştu');
}

/* Listeye yönlendir */
header(
    'Location: /alintilar/admin/kitap-listele.php?deleted=1&quotes=' . $quoteCount
);
exit;
