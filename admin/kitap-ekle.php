<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/auth.php';

$hata = '';
$basari = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title  = trim($_POST['title'] ?? '');
    $author = trim($_POST['author'] ?? '');

    if ($title === '' || $author === '') {
        $hata = 'Kitap adı ve yazar zorunludur.';
    } else {
        // 1️⃣ Daha önce var mı?
        $check = $pdo->prepare("
            SELECT id FROM books
            WHERE title = :title AND author = :author
            LIMIT 1
        ");
        $check->execute([
            'title'  => $title,
            'author' => $author
        ]);

        if ($check->fetch()) {
            $hata = 'Bu kitap zaten veritabanında mevcut.';
        } else {

            // 2️⃣ Görselleri klasöre kaydet
            $uploadDir = __DIR__ . '/../covers/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $coverImagePath = null;
            $authorImagePath = null;

            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('cover_', true) . '.' . $ext;
                move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadDir . $filename);
                $coverImagePath = 'covers/' . $filename;
            }

            if (isset($_FILES['author_image']) && $_FILES['author_image']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['author_image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('author_', true) . '.' . $ext;
                move_uploaded_file($_FILES['author_image']['tmp_name'], $uploadDir . $filename);
                $authorImagePath = 'covers/' . $filename;
            }

            // 3️⃣ Veritabanına ekle
            $stmt = $pdo->prepare("
                INSERT INTO books (
                    title,
                    author,
                    cover_image,
                    author_image,
                    created_at,
                    updated_at
                ) VALUES (
                    :title,
                    :author,
                    :cover_image,
                    :author_image,
                    NOW(),
                    NOW()
                )
            ");

            $ok = $stmt->execute([
                'title'       => $title,
                'author'      => $author,
                'cover_image' => $coverImagePath,
                'author_image'=> $authorImagePath
            ]);

            if ($ok) {
                $basari = 'Kitap başarıyla eklendi.';
            } else {
                $hata = 'Veritabanı hatası.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kitap Ekle</title>
</head>
<body>

<h1>Kitap Ekle</h1>

<?php if ($hata): ?>
    <p style="color:red"><?= htmlspecialchars($hata) ?></p>
<?php endif; ?>

<?php if ($basari): ?>
    <p style="color:green"><?= htmlspecialchars($basari) ?></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <label>Kitap Adı</label><br>
    <input type="text" name="title"><br><br>

    <label>Yazar</label><br>
    <input type="text" name="author"><br><br>

    <label>Kitap Kapak Görseli</label><br>
    <input type="file" name="cover_image" accept="image/*"><br><br>

    <label>Yazar Görseli</label><br>
    <input type="file" name="author_image" accept="image/*"><br><br>

    <button type="submit">Kaydet</button>

    <br><br>
    <a href="/alintilar/index.php" style="display:inline-block; padding:5px 5px; background:#ffcc00; color:#000; border-radius:4px; text-decoration:none;">Index Sayfasına Dön</a>

</form>

</body>
</html>
