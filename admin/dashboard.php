<?php
require_once __DIR__ . '/../config/auth.php';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 40px;
        }
        .container {
            max-width: 700px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 6px;
        }
        h1 {
            margin-bottom: 30px;
            text-align: center;
        }
        h2 {
            margin-top: 30px;
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .button {
            display: block;
            padding: 12px;
            margin-bottom: 10px;
            background: #222;
            color: #fff;
            text-decoration: none;
            text-align: center;
            border-radius: 4px;
        }
        .button:hover {
            background: #444;
        }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="container">
    <h1>Admin Panel</h1>

    <h2>📚 Kitap Yönetimi</h2>
    <a class="button" href="/alintilar/admin/kitap-ekle.php">
        Kitap Ekle
    </a>
    <a class="button" href="/alintilar/admin/kitap-listele.php">
        Kitapları Listele
    </a>

    <h2>✍️ Alıntı Yönetimi</h2>
    <a class="button" href="/alintilar/admin/alinti-ekle.php">
        Alıntı Ekle
    </a>
    <a class="button" href="/alintilar/admin/alinti-listele.php">
        Alıntıları Listele
    </a>
</div>

</body>
</html>
