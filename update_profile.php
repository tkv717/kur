<?php
session_start();

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

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    // Если пользователь не авторизован, возвращаем ошибку
    echo json_encode(['success' => false, 'message' => 'Пользователь не авторизован']);
    exit();
}

// Получение имени пользователя из сессии
$username = $_SESSION['login'];

// Получение данных из POST-запроса
$field = $_POST['field'];
$value = $_POST['value'];

// Обновление данных пользователя
$query = "UPDATE users SET $field = '$value' WHERE login = '$username'";
$result = $conn->query($query);

if ($result) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => true, 'message' => 'Данные успешно обновлены']);
} else {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'message' => 'Произошла ошибка при обновлении данных']);
}

// Закрытие соединения с базой данных
$conn->close();
?>
