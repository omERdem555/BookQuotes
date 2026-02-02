<?php
require_once __DIR__ . '/config/database.php';

$active = 'alintilar';

/* ID kontrolü */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Kitap belirtilmedi');
}

$bookId = (int) $_GET['id'];

/* Kitabı al */
$stmt = $pdo->prepare("
    SELECT id, 
           title, 
           author,
           cover_image,
           author_image
    FROM books
    WHERE id = ?
    LIMIT 1
");
$stmt->execute([$bookId]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    die('Kitap bulunamadı');
}

/* Kitaba ait yayınlanmış alıntılar */
$stmt = $pdo->prepare("
    SELECT
        id,
        title,
        quote_text,
        page_number
    FROM quotes
    WHERE book_id = ?
      AND is_published = 1
    ORDER BY created_at DESC
");
$stmt->execute([$bookId]);
$quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($book['title']) ?></title>

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


header a {
    color: #fff;
    text-decoration: none;
    font-size: 14px;
    opacity: .8;
}

header a:hover {
    opacity: 1;
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

.nav-link:hover {
    opacity: 1;
}

.nav-link.active {
    opacity: 1;
}

.nav-link.active::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    height: 2px;
    background: #fff;
}

.divider {
    width: 1px;
    height: 14px;
    background: #444;
}

.container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.layout {
    display: grid;
    grid-template-columns: 220px 1fr 220px;
    gap: 30px;
}

/* Sol ve sağ görseller sabit */
.side {
    position: sticky;
    top: 20px; /* header boşluğu kadar */
    align-self: start;
}

.side img {
    width: 100%;
    border-radius: 6px;
    display: block;
}


/* Mobil */
@media (max-width: 768px) {
    .book-layout {
        grid-template-columns: 1fr;
    }

    .book-image,
    .author-image {
        text-align: center;
    }

    .book-image img,
    .author-image img {
        max-width: 160px;
    }
}

body {
    font-size: 16px;
}

@media (max-width: 768px) {
    body {
        font-size: 15px;
    }
}


/* Alıntılar scroll değil, her alıntı kendi kutusu içinde alt alta */
.quotes-area {
    display: flex;
    flex-direction: column;
}

/* Her alıntı kutusu */
.quote {
    background: #1b1b1b;
    font-size: 20px;
    padding: 24px;
    margin-bottom: 30px;
    border-left: 3px solid #ffcc00;
    border-radius: 4px;
}

.quote-text {
    font-size: 15px;
    line-height: 1.6;
    margin-bottom: 16px;
    white-space: pre-line;
}

.quote-meta {
    font-size: 13px;
    opacity: .75;
}

.quote-meta span {
    margin-right: 12px;
}
</style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<header>
    <nav class="nav">
        <span class="divider"></span>
        <a href="/alintilar/index.php" class="nav-link">Kitaplar</a>
        <span class="divider"></span>
        <a href="/alintilar/kitaplar.php" class="nav-link <?= $active === 'kitaplar' ? 'active' : '' ?>">Kitaplar</a>
        <span class="divider"></span>
        <a href="/alintilar/admin/login.php" class="nav-link">Admin Giriş</a>
    </nav>
</header>

<div class="container">
<h1><?= htmlspecialchars($book['title']) ?></h1>
<p style="opacity:.7"><?= htmlspecialchars($book['author']) ?></p>

<?php if (!$quotes): ?>
    <p>Bu kitap için yayınlanmış alıntı yok.</p>
<?php else: ?>
<div class="layout">
    <div class="side left">
        <?php if ($book['cover_image']): ?>
            <img src="<?= htmlspecialchars($book['cover_image']) ?>" alt="Kitap Kapak">
        <?php endif; ?>
    </div>

    <div class="quotes-area">
        <?php foreach ($quotes as $q): ?>
            <div class="quote">
                <?php if ($q['title']): ?>
                    <div class="quote-title"><strong><?= htmlspecialchars($q['title']) ?></strong></div>
                <?php endif; ?>
                <br>
                <div class="quote-text"><?= nl2br(htmlspecialchars($q['quote_text'])) ?></div>

                <?php if ($q['page_number']): ?>
                    <div class="quote-meta"><span>Sayfa: <?= (int)$q['page_number'] ?></span></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="side right">
        <?php if ($book['author_image']): ?>
            <img src="<?= htmlspecialchars($book['author_image']) ?>" alt="Yazar">
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
</div>

</body>
</html>
