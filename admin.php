<?php
session_start();
if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
    header('Location: login.php');
    exit();
}
// Проверка является ли пользователь администратором
if ($_SESSION['login'] !== 'admin') {
    echo "Доступ запрещен.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Админ-панель</h1>
    <div class="buttons">
        <a href="edit_users.php" class="btn">Пользователи</a>
        <a href="edit_insurance.php" class="btn">Страховки</a>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
