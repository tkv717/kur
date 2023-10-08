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

// Проверка, если форма была отправлена для редактирования, удаления или создания нового страхового типа
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка наличия insurance_id в POST-запросе
    if (isset($_POST['insurance_id'])) {
        $insuranceId = $_POST['insurance_id'];

        if (isset($_POST['edit_insurance'])) {
            // Обработка редактирования страхового типа
            $newName = $_POST['name'];
            $newCoefficient = $_POST['coefficient'];

            $sql = "UPDATE insurance_types SET name='$newName', coefficient='$newCoefficient' WHERE id=$insuranceId";
            if ($conn->query($sql) === TRUE) {
                echo "Страховой тип успешно обновлен.";
            } else {
                echo "Ошибка при обновлении страхового типа: " . $conn->error;
            }
        } elseif (isset($_POST['delete_insurance'])) {
            // Обработка удаления страхового типа
            $sql = "DELETE FROM insurance_types WHERE id=$insuranceId";
            if ($conn->query($sql) === TRUE) {
                echo "Страховой тип успешно удален.";
            } else {
                echo "Ошибка при удалении страхового типа: " . $conn->error;
            }
        }
    } elseif (isset($_POST['create_insurance'])) {
        // Обработка создания нового страхового типа
        if (isset($_POST['new_name']) && isset($_POST['new_coefficient'])) {
            $newName = $_POST['new_name'];
            $newCoefficient = $_POST['new_coefficient'];

            $sql = "INSERT INTO insurance_types (name, coefficient) VALUES ('$newName', '$newCoefficient')";
            if ($conn->query($sql) === TRUE) {
                echo "Новый страховой тип успешно создан.";
            } else {
                echo "Ошибка при создании нового страхового типа: " . $conn->error;
            }
        } else {
            echo "Ошибка: отсутствуют данные для создания нового страхового типа.";
        }
    }
}

// Получение данных страховых типов из базы данных
$sql = "SELECT * FROM insurance_types";
$result = $conn->query($sql);

$insuranceTypes = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $insuranceTypes[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Страховки</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Страховки</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Coefficient</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($insuranceTypes as $insuranceType): ?>
                <tr>
                    <td><?php echo $insuranceType['id']; ?></td>
                    <td><?php echo $insuranceType['name']; ?></td>
                    <td><?php echo $insuranceType['coefficient']; ?></td>
                    <td>
                        <form action="" method="POST">
                            <input type="hidden" name="insurance_id" value="<?php echo $insuranceType['id']; ?>">
                            <input type="text" name="name" value="<?php echo $insuranceType['name']; ?>">
                            <input type="text" name="coefficient" value="<?php echo $insuranceType['coefficient']; ?>">
                            <button type="submit" name="edit_insurance">Изменить</button>
                            <button type="submit" name="delete_insurance">Удалить</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <form action="" method="POST">
                    <td></td>
                    <td>
                        <input type="text" name="new_name" placeholder="Название страхового типа">
                    </td>
                    <td>
                        <input type="text" name="new_coefficient" placeholder="Коэффициент">
                    </td>
                    <td>
                        <button type="submit" name="create_insurance">Создать</button>
                    </td>
                </form>
            </tr>
        </tbody>
    </table>

    <a href="admin.php">Админ-панель</a>

        <?php include 'footer.php'; ?>

</body>
</html>
