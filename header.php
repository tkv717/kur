<?php
session_start();

$loggedIn = false;
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) {
    $loggedIn = true;
}
?>

<header class="header">
    <div class="container">
        <div class="logo">
            <h1><a href="index.php">SafeGuard</a></h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Главная</a></li>
                <li><a href="about.php">О компании</a></li>
                <li><a href="contact.php">Контакты</a></li>
            </ul>
            <?php if ($loggedIn) : ?>
                <ul class="login-register">
                    <?php if ($_SESSION['login'] === 'admin') : ?>
                        <li><a href="admin.php" class="btn">Админ панель</a></li>
                    <?php endif; ?>
                    <li><a href="dashboard.php" class="btn">Личный кабинет</a></li>
                    <li><a href="logout.php" class="btn">Выход</a></li>
                </ul>
            <?php else : ?>
                <ul class="login-register">
                    <li><a href="login.php" class="btn">Вход</a></li>
                    <li><a href="registration.php" class="btn">Регистрация</a></li>
                </ul>
            <?php endif; ?>
        </nav>
    </div>
</header>
