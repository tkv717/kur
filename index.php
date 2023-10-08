<?php
session_start();
// Проверяем, авторизован ли пользователь
$loggedIn = false;
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) {
    $loggedIn = true;
}
// Функция для перенаправления на страницу входа
function redirectToLogin() {
    header('Location: login.php');
    exit();
}
// Функция для перенаправления на страницу страховки
function redirectToInsurance() {
    header('Location: insurance.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SafeGuard</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <section class="hero">
        <div class="container">
            <h1>Добро пожаловать!</h1>
            <p>Мы предлагаем широкий спектр страховых услуг для вашей защиты.</p>
        </div>
    </section>
    <section class="features">
        <div class="container">
            <h2>Наши преимущества</h2>
            <div class="feature">
                <h3>Широкий спектр страховых услуг</h3>
                <p>Мы предлагаем различные виды страхования, чтобы удовлетворить потребности каждого клиента.</p>
            </div>
            <div class="feature">
                <img src="img/sec1.png" alt="Преимущество 2">
                <h3>Высокий уровень надежности</h3>
                <p>Мы гарантируем надежную защиту и быструю выплату компенсаций в случае страхового случая.</p>
            </div>
            <div class="feature">
                <img src="img/agent.png" alt="Преимущество 3">
                <h3>Профессиональные страховые агенты</h3>
                <p>Наша команда опытных агентов поможет вам выбрать оптимальный план страхования для ваших потребностей.</p>
            </div>
        </div>
    </section>
    <section id="services" class="services">
        <div class="container">
            <h2>Наши услуги</h2>
            <div class="service">
                <img src="img/car.png" alt="Услуга 1">
                <h3>Страхование автомобиля</h3>
                <p>Защитите свое авто от возможных рисков и неожиданных ситуаций на дороге.</p>
                <?php if ($loggedIn): ?>
                    <a href="insurance.php" class="btn">Оформить</a>
                <?php else: ?>
                    <a href="login.php" class="btn">Оформить</a>
                <?php endif; ?>
            </div>
            <div class="service">
                <img src="img/health.png" alt="Услуга 2">
                <h3>Медицинское страхование</h3>
                <p>Обеспечьте себя и свою семью медицинской защитой и доступом к качественной медицинской помощи.</p>
                <?php if ($loggedIn): ?>
                    <a href="insurance.php" class="btn">Оформить</a>
                <?php else: ?>
                    <a href="login.php" class="btn">Оформить</a>
                <?php endif; ?>
            </div>
            <div class="service">
                <img src="img/house.png" alt="Услуга 3">
                <h3>Страхование недвижимости</h3>
                <p>Защитите ваше имущество от повреждений, кражи и других непредвиденных ситуаций.</p>
                <?php if ($loggedIn): ?>
                    <a href="insurance.php" class="btn">Оформить</a>
                <?php else: ?>
                    <a href="login.php" class="btn">Оформить</a>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php include 'footer.php'; ?>
</body>
</html>
