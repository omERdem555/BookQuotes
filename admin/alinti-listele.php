<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';

$sql = "
    SELECT
        q.id,
        q.title AS quote_title,
        q.page_number,
        q.is_published,
        q.created_at,
        b.title AS book_title,
        b.author
    FROM quotes q
    INNER JOIN books b ON b.id = q.book_id
    ORDER BY q.created_at DESC
";

$stmt = $pdo->query($sql);
$quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Alıntılar</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 8px; border: 1px solid #ccc; }
        th { background: #eee; }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<h1>Alıntılar</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Alıntı Başlığı</th>
            <th>Kitap</th>
            <th>Yazar</th>
            <th>Sayfa</th>
            <th>Durum</th>
            <th>Tarih</th>
            <th>İşlem</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($quotes as $q): ?>
        <tr>
            <td><?= (int)$q['id'] ?></td>
            <td><?= htmlspecialchars($q['quote_title']) ?></td>
            <td><?= htmlspecialchars($q['book_title']) ?></td>
            <td><?= htmlspecialchars($q['author']) ?></td>
            <td><?= $q['page_number'] ?: '-' ?></td>
            <td>
                <?= $q['is_published'] ? 'Yayında' : 'Taslak' ?>
            </td>
            <td><?= $q['created_at'] ?></td>
            <td>
                <form method="post"
                    action="alinti-duzenle.php?id=<?= (int)$q['id'] ?>"
                    style="display:inline;"
                    onsubmit="return confirm('Bu alıntıyı düzenlemek istiyor musun?');">
                 <input type="hidden" name="id" value="<?= (int)$q['id'] ?>">
                 <button type="submit">Düzenle</button>
                </form>

                <form method="post"
                    action="alinti-silme.php"
                    style="display:inline;"
                    onsubmit="return confirm('Bu alıntıyı silmek istiyor musun?');">
                 <input type="hidden" name="id" value="<?= (int)$q['id'] ?>">
                 <button type="submit">Sil</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>

    </tbody>
</table>
    <br><br>
    <a href="/alintilar/index.php" style="display:inline-block; padding:5px 5px; background:#ffcc00; color:#000; border-radius:4px; text-decoration:none;">Index Sayfasına Dön</a>
</body>
</html>
