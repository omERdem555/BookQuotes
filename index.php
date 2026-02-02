<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/helpers.php';

$active = 'alintilar';

$sql = "
    SELECT
        q.id,
        q.title AS quote_title,
        q.quote_text,
        q.page_number,
        q.created_at,
        b.id AS book_id,
        b.title AS book_title,
        b.author,
        b.cover_image
    FROM quotes q
    INNER JOIN books b ON b.id = q.book_id
    WHERE q.is_published = 1
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
body {
    margin: 0;
    font-family: system-ui, -apple-system, BlinkMacSystemFont;
    background: #121212;
    color: #eaeaea;
}

header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 40px;
    border-bottom: 1px solid #222;
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

.nav-link:hover { opacity:1; }
.nav-link.active { opacity:1; }
.nav-link.active::after {
    content:"";
    position:absolute;
    left:0;
    bottom:0;
    width:100%;
    height:2px;
    background:#fff;
}

.divider { width:1px; height:14px; background:#444; }

.container {
    max-width: 1000px;
    margin: 40px auto;
    padding: 0 20px;
}

.quote {
    display: flex;
    gap: 20px;
    background: #1b1b1b;
    padding: 20px;
    margin-bottom: 30px;
    border-left: 3px solid #ffcc00;
    border-radius: 4px;
    align-items: flex-start;
}

.quote img {
    width: 60px;
    height: 90px;
    object-fit: cover;
    border-radius: 4px;
    flex-shrink: 0;
}

.quote-content {
    flex: 1;
}

.quote-title {
    font-size: 16px;
    margin-bottom: 10px;
    font-weight: bold;
}

.quote-text {
    font-size: 15px;
    line-height: 1.6;
    margin-bottom: 12px;
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
</head>
<body>

<header>
    <nav class="nav">
        <span class="divider"></span>
        <a href="/alintilar/index.php" class="nav-link <?= $active === 'alintilar' ? 'active' : '' ?>">Alıntılar</a>
        <span class="divider"></span>
        <a href="/alintilar/kitaplar.php" class="nav-link">Kitaplar</a>
        <span class="divider"></span>
        <a href="/alintilar/admin/login.php" class="nav-link">Admin Giriş</a>
    </nav>
</header>

<div class="container">
<?php if (!$quotes): ?>
    <p>Henüz yayınlanmış alıntı yok.</p>
<?php endif; ?>

<?php foreach ($quotes as $q): ?>
    <div class="quote">
        <?php if ($q['cover_image']): ?>
            <img src="<?= htmlspecialchars($q['cover_image']) ?>" alt="<?= htmlspecialchars($q['book_title']) ?>">
        <?php endif; ?>

        <div class="quote-content">
            <?php if ($q['quote_title']): ?>
                <div class="quote-title"><?= htmlspecialchars($q['quote_title']) ?></div>
            <?php endif; ?>

            <div class="quote-text"><?= nl2br(htmlspecialchars($q['quote_text'])) ?></div>

            <div class="quote-meta">
                <span><?= htmlspecialchars($q['book_title']) ?> - <?= htmlspecialchars($q['author']) ?></span>
                <?php if ($q['page_number']): ?>
                    <span>Sayfa <?= (int)$q['page_number'] ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>

</body>
</html>