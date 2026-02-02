<?php
require_once __DIR__ . '/config/database.php';

$active = 'kitaplar';

$sql = "
    SELECT
        b.id,
        b.title,
        b.author,
        b.cover_image,
        COUNT(q.id) AS quote_count
    FROM books b
    INNER JOIN quotes q ON q.book_id = b.id
    WHERE q.is_published = 1
    GROUP BY b.id, b.title, b.author, b.cover_image
    ORDER BY b.title ASC
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
body {
    margin: 0;
    font-family: system-ui, -apple-system, BlinkMacSystemFont;
    background: #121212;
    color: #eaeaea;
}

header {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    align-items: center;
}

header a {
    position: relative;
    padding-bottom: 6px;
    font-size: 15px;
}

/* Ayırıcı çizgi */
header a:not(:last-child)::after {
    content: '|';
    position: absolute;
    right: -10px;
    opacity: .4;
}

/* Aktif sayfa */
header a.active::before {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    height: 2px;
    background: #fff;
}


.nav {
    display: flex;
    align-items: center;
    gap: 12px;
}

.nav-link {
    position: relative;
    padding-bottom: 6px;
    color: #fff;
    text-decoration: none;
    opacity: .75;
}

.nav-link:hover { opacity: 1; }
.nav-link.active { opacity: 1; }
.nav-link.active::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    height: 2px;
    background: #fff;
}

.divider { width: 1px; height: 14px; background: #444; }

.container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.book {
    display: flex;
    justify-content: space-between;
    align-items: flex-start; /* üstten hizalı */
    background: #1b1b1b;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.book-info {
    flex: 1;
}

.book-info a {
    color: #ffffff;
    text-decoration: none;
    font-size: 23px;
}

.book-info a:hover {
    text-decoration: underline;
}

.meta {
    font-size: 15px;
    opacity: .7;
    margin-top: 6px;
}

.book img {
    width: 160px;
    height: 240px;
    object-fit: cover;
    border-radius: 6px;
    margin-left: 20px;
    flex-shrink: 0;
}

</style>
</head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<body>

<header>
    <nav class="nav">
        <a href="/alintilar/index.php" class="nav-link">Alıntılar</a>
        <span class="divider"></span>
        <a href="/alintilar/kitaplar.php" class="nav-link <?= $active === 'kitaplar' ? 'active' : '' ?>">Kitaplar</a>
        <span class="divider"></span>
        <a href="/alintilar/admin/login.php" class="nav-link">Admin Giriş</a>
    </nav>
</header>

<div class="container">
<h1>Kitaplar</h1>

<?php if (!$books): ?>
    <p>Henüz alıntılanmış kitap yok.</p>
<?php endif; ?>

<?php foreach ($books as $book): ?>
    <div class="book">
        <div class="book-info">
            <a href="/alintilar/kitap.php?id=<?= (int)$book['id'] ?>"> 
                <strong><?= htmlspecialchars($book['title']) ?></strong>
            </a>
            <div class="meta">
                <?= htmlspecialchars($book['author']) ?> · <?= (int)$book['quote_count'] ?> alıntı
            </div>
        </div>

        <?php if ($book['cover_image']): ?>
            <img src="<?= htmlspecialchars($book['cover_image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
        <?php endif; ?>
    </div>
<?php endforeach; ?>

</div>
</body>
</html>
