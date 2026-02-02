<?php $active = 'admin'; ?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Giriş</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<h2>Admin Giriş</h2>

<form method="post" action="/alintilar/actions/login_action.php">
    <div>
        <label>Kullanıcı Adı</label><br>
        <input type="text" name="username" required>
    </div>

    <div>
        <label>Şifre</label><br>
        <input type="password" name="password" required>
    </div>

    <button type="submit">Giriş Yap</button>
</form>

    <br><br>
    <a href="/alintilar/index.php" style="display:inline-block; padding:5px 5px; background:#ffcc00; color:#000; border-radius:4px; text-decoration:none;">Index Sayfasına Dön</a>


</body>
</html>
