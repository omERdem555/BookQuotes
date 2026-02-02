<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';

/* 1️⃣ ID kontrolü */
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die('Geçersiz alıntı ID');
}

/* 2️⃣ Alıntıyı getir */
$stmt = $pdo->prepare("
    SELECT
        q.id,
        q.book_id,
        q.title,
        q.quote_text,
        q.page_number,
        q.is_published
    FROM quotes q
    WHERE q.id = :id
    LIMIT 1
");
$stmt->execute(['id' => $id]);
$quote = $stmt->fetch();

if (!$quote) {
    die('Alıntı bulunamadı');
}

/* 3️⃣ Kitap listesini getir */
$booksStmt = $pdo->query("
    SELECT id, title, author
    FROM books
    ORDER BY title ASC
");
$books = $booksStmt->fetchAll();

/* 4️⃣ Güncelleme */
$hata = '';
$basari = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id      = (int)($_POST['book_id'] ?? 0);
    $title        = trim($_POST['title'] ?? '');
    $quote_text   = trim($_POST['quote_text'] ?? '');
    $page_number  = (int)($_POST['page_number'] ?? 0);
    $is_published = isset($_POST['is_published']) ? 1 : 0;

    if ($book_id <= 0 || $title === '' || $quote_text === '') {
        $hata = 'Kitap, başlık ve alıntı metni zorunludur.';
    } else {
        $upd = $pdo->prepare("
            UPDATE quotes
            SET
                book_id = :book_id,
                title = :title,
                quote_text = :quote_text,
                page_number = :page_number,
                is_published = :is_published,
                updated_at = NOW()
            WHERE id = :id
        ");

        $ok = $upd->execute([
            'book_id'      => $book_id,
            'title'        => $title,
            'quote_text'   => $quote_text,
            'page_number'  => $page_number ?: null,
            'is_published' => $is_published,
            'id'           => $id
        ]);

        if ($ok) {
            $basari = 'Alıntı başarıyla güncellendi.';
            // Formda güncel veriyi göstermek için
            $quote = array_merge($quote, $_POST, [
                'book_id' => $book_id,
                'is_published' => $is_published
            ]);
        } else {
            $hata = 'Güncelleme sırasında hata oluştu.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Alıntı Düzenle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>

<h1>Alıntı Düzenle</h1>

<?php if ($hata): ?>
    <p style="color:red"><?= htmlspecialchars($hata) ?></p>
<?php endif; ?>

<?php if ($basari): ?>
    <p style="color:green"><?= htmlspecialchars($basari) ?></p>
<?php endif; ?>

<form method="post">

    <label>Kitap</label><br>
    <select name="book_id" required>
        <option value="">-- Seçiniz --</option>
        <?php foreach ($books as $b): ?>
            <option value="<?= $b['id'] ?>"
                <?= $b['id'] == $quote['book_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($b['title']) ?> — <?= htmlspecialchars($b['author']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Alıntı Başlığı</label><br>
    <input type="text" name="title"
           value="<?= htmlspecialchars($quote['title']) ?>" required><br><br>

    <label>Alıntı Metni</label><br>
    <textarea name="quote_text" rows="6" required><?= htmlspecialchars($quote['quote_text']) ?></textarea><br><br>

    <label>Sayfa Numarası</label><br>
    <input type="number" name="page_number"
           value="<?= (int)$quote['page_number'] ?>"><br><br>

    <label>
        <input type="checkbox" name="is_published"
            <?= $quote['is_published'] ? 'checked' : '' ?>>
        Yayında
    </label><br><br>

    <button type="submit">Güncelle</button>

</form>

<p>
    <a href="alinti-listele.php">← Listeye dön</a>
</p>

</body>
</html>
