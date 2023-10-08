<?php
session_start();
if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
    header('Location: login.php');
    exit();
}

// Проверка, является ли пользователь администратором
if ($_SESSION['login'] !== 'admin') {
    echo "Доступ запрещен.";
    exit();
}

// Подключение к базе данных
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tkvbd";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

// Проверка, если форма была отправлена для редактирования или удаления пользователя
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка наличия user_id в POST-запросе
    if (isset($_POST['user_id'])) {
        $userId = $_POST['user_id'];

        if (isset($_POST['edit_user'])) {
            // Обработка редактирования пользователя
            $newUsername = $_POST['username'];
            $newName = $_POST['name'];
            $newEmail = $_POST['email'];

            $sql = "UPDATE users SET login='$newUsername', name='$newName', email='$newEmail' WHERE id=$userId";
            if ($conn->query($sql) === TRUE) {
                echo "Пользователь успешно обновлен.";
            } else {
                echo "Ошибка при обновлении пользователя: " . $conn->error;
            }
        } elseif (isset($_POST['delete_user'])) {
            // Обработка удаления пользователя
            $sql = "DELETE FROM users WHERE id=$userId";
            if ($conn->query($sql) === TRUE) {
                echo "Пользователь успешно удален.";
            } else {
                echo "Ошибка при удалении пользователя: " . $conn->error;
            }
        }
    }
}

// Получение данных пользователей из базы данных
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

$users = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Пользователи</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Пользователи</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Name</th>
                <th>Email</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['login']; ?></td>
                    <td><?php echo $user['name']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td>
                        <form action="" method="POST">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <input type="text" name="username" value="<?php echo $user['login']; ?>">
                            <input type="text" name="name" value="<?php echo $user['name']; ?>">
                            <input type="email" name="email" value="<?php echo $user['email']; ?>">
                            <button type="submit" name="edit_user">Изменить</button>
                            <button type="submit" name="delete_user">Удалить</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a href="admin.php">Админ-панель</a>

    <?php include 'footer.php'; ?>
</body>
</html>
