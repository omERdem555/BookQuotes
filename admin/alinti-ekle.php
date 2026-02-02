<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';

$hata = '';
$basari = '';

// Kitapları çek
$kitaplar = $pdo->query("
    SELECT id, title, author
    FROM books
    ORDER BY title ASC
")->fetchAll();

if (!$kitaplar) {
    $hata = 'Henüz kitap eklenmeden alıntı eklenemez.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$hata) {

    $book_id      = intval($_POST['book_id'] ?? 0);
    $title        = trim($_POST['title'] ?? '');
    $quote_text   = trim($_POST['quote_text'] ?? '');
    $page_number  = trim($_POST['page_number'] ?? '');
    $is_published = isset($_POST['is_published']) ? 1 : 0;

    if ($book_id <= 0 || $title === '' || $quote_text === '') {
        $hata = 'Kitap, alıntı başlığı ve alıntı metni zorunludur.';
    } else {

        // Book gerçekten var mı?
        $check = $pdo->prepare("
            SELECT id FROM books WHERE id = :id LIMIT 1
        ");
        $check->execute(['id' => $book_id]);

        if (!$check->fetch()) {
            $hata = 'Geçersiz kitap seçimi.';
        } else {

            $stmt = $pdo->prepare("
                INSERT INTO quotes
                (book_id, title, quote_text, page_number, is_published, created_at, updated_at)
                VALUES
                (:book_id, :title, :quote_text, :page_number, :is_published, NOW(), NOW())
            ");

            $ok = $stmt->execute([
                'book_id'      => $book_id,
                'title'        => $title,
                'quote_text'   => $quote_text,
                'page_number'  => $page_number !== '' ? $page_number : null,
                'is_published' => $is_published
            ]);

            if ($ok) {
                $basari = 'Alıntı başarıyla eklendi.';
            } else {
                $hata = 'Kayıt sırasında hata oluştu.';
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Alıntı Ekle</title>
</head>
<body>

<h1>Alıntı Ekle</h1>

<?php if ($hata): ?>
    <p style="color:red"><?= htmlspecialchars($hata) ?></p>
<?php endif; ?>

<?php if ($basari): ?>
    <p style="color:green"><?= htmlspecialchars($basari) ?></p>
<?php endif; ?>

<?php if ($kitaplar): ?>
<form method="post">

    <label>Kitap</label><br>
    <select name="book_id" required>
        <option value="">-- Kitap Seç --</option>
        <?php foreach ($kitaplar as $kitap): ?>
            <option value="<?= $kitap['id'] ?>">
                <?= htmlspecialchars($kitap['title']) ?> — <?= htmlspecialchars($kitap['author']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <label>Alıntı Başlığı</label><br>
    <input type="text" name="title" required>
    <br><br>

    <label>Alıntı Metni</label><br>
    <textarea name="quote_text" rows="5" required></textarea>
    <br><br>

    <label>Sayfa Numarası (opsiyonel)</label><br>
    <input type="number" name="page_number">
    <br><br>

    <label>
        <input type="checkbox" name="is_published" value="1">
        Yayınla
    </label>
    <br><br>

    <button type="submit">Kaydet</button>

    <br><br>
    <a href="/alintilar/index.php" style="display:inline-block; padding:5px 5px; background:#ffcc00; color:#000; border-radius:4px; text-decoration:none;">Index Sayfasına Dön</a>

</form>
<?php endif; ?>

</body>
</html>
