<?php
session_start();

// Уничтожение сессии
session_unset();
session_destroy();

// Перенаправление на страницу входа
header('Location: login.php');
exit();
?>
