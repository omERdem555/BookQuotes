<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/database.php';

/*
    Kitapları al + her kitap için alıntı sayısını getir
*/
$sql = "
    SELECT 
        b.id,
        b.title,
        b.author,
        COUNT(q.id) AS quote_count
    FROM books b
    LEFT JOIN quotes q ON q.book_id = b.id
    GROUP BY b.id
    ORDER BY b.created_at DESC
";

$stmt = $pdo->query($sql);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kitaplar</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { padding: 8px; border: 1px solid #ccc; }
        th { background: #eee; }
    </style>
</head>
<body>

<h1>Kitaplar</h1>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Başlık</th>
            <th>Yazar</th>
            <th>Alıntı Sayısı</th>
            <th>İşlem</th>
        </tr>
    </thead>
    <tbody>

    <?php foreach ($books as $book): ?>
        <tr>
            <td><?= (int)$book['id'] ?></td>
            <td><?= htmlspecialchars($book['title']) ?></td>
            <td><?= htmlspecialchars($book['author']) ?></td>
            <td><?= (int)$book['quote_count'] ?></td>
            <td>
                <form method="post"
                      action="kitap-silme.php"
                      onsubmit="return confirm(
                        'Bu kitabı silersen <?= (int)$book['quote_count'] ?> alıntı da silinecek. Emin misin?'
                      );"
                      style="display:inline;">
                      
                    <input type="hidden" name="id" value="<?= (int)$book['id'] ?>">
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
