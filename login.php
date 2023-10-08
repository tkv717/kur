<?php
session_start(); // Начало сессии

// Подключение к базе данных
$host = 'localhost'; // Имя хоста базы данных
$username = 'root'; // Ваше имя пользователя базы данных
$password = ''; // Ваш пароль базы данных
$dbName = 'tkvbd'; // Имя базы данных

// Установка соединения с базой данных
$conn = new mysqli($host, $username, $password, $dbName);

// Проверка соединения
if ($conn->connect_error) {
    die('Ошибка подключения к базе данных: ' . $conn->connect_error);
}

// Обработка отправленной формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Проверка, что поля формы не пустые
    if (!empty($login) && !empty($password)) {
        // Поиск пользователя в базе данных
        $query = "SELECT * FROM users WHERE login='$login'";
        $result = $conn->query($query);

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];

            // Проверка соответствия пароля
            if (password_verify($password, $hashed_password)) {
                // Успешная авторизация
                $_SESSION['loggedIn'] = true;
                $_SESSION['id'] = $row['id'];
                $_SESSION['login'] = $login;

                // Перенаправление на личный кабинет или другую страницу
                header('Location: dashboard.php');
                exit();
            } else {
                echo 'Неверное имя пользователя или пароль!';
            }
        } else {
            echo 'Неверное имя пользователя или пароль!';
        }
    } else {
        echo 'Пожалуйста, заполните все поля формы.';
    }
}

// Закрытие соединения с базой данных
$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="login">
        <div class="container">
            <h2>Вход</h2>
                <form action="" method="POST" style="display: flex; flex-direction: column; align-items: flex-start;">
                <input type="text" name="login" placeholder="Логин" required>
                <input type="password" name="password" placeholder="Пароль" required>
                <button type="submit" class="btn">Войти</button>
                <p>Еще не зарегистрированы? <a href="registration.php">Создать аккаунт</a></p>
            </form>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
