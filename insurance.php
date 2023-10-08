<?php
session_start();
if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['id'];

// Подключение к базе данных
$host = "localhost";
$username = "root";
$password = "";
$dbName = "tkvbd";

$conn = new mysqli($host, $username, $password, $dbName);
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

// Обработка отправленной формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $insuranceType = $_POST['insurance_type'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $insuranceAmount = $_POST['insurance_amount'];

    // Получение коэффициента страховки из базы данных
    $sql = "SELECT coefficient FROM insurance_types WHERE name = '$insuranceType'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $coefficient = $row['coefficient'];

        // Вычисление суммы выплаты при страховом случае
        $payoutAmount = $insuranceAmount * $coefficient;

        // Вставка данных о страховке в таблицу insurance_history
        $insertSql = "INSERT INTO insurance_history (user_id, insurance_type, status, start_date, end_date, insurance_amount, payout_amount)
                      VALUES ('$userId', '$insuranceType', 'active', '$startDate', '$endDate', '$insuranceAmount', '$payoutAmount')";
        if ($conn->query($insertSql) === TRUE) {
            echo "<script>alert('Страховка успешно оформлена!'); window.location.href='dashboard.php';</script>";
        } else {
            echo "Ошибка при оформлении страховки: " . $conn->error;
        }
    } else {
        echo "Ошибка при получении данных о страховке";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформление страховки</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
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
                <ul class="login-register">
                    <li><a href="dashboard.php" class="btn">Личный кабинет</a></li>
                    <li><a href="logout.php" class="btn">Выход</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="insurance">
        <div class="container">
            <h2>Оформление страховки</h2>
            <form action="" method="POST" style="
                display: flex;
                flex-direction: column;
                align-items: flex-start;
            ">
                <label for="insurance_type">Тип страховки:</label>
                <select name="insurance_type" id="insurance_type" required>
                    <?php
                    $sql = "SELECT name FROM insurance_types";
                    $result = $conn->query($sql);
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $insuranceName = $row['name'];
                            echo "<option value='$insuranceName'>$insuranceName</option>";
                        }
                    }
                    ?>
                </select>

                <label for="start_date">Дата начала страховки:</label>
                <input type="date" name="start_date" id="start_date" required>

                <label for="end_date">Дата окончания страховки:</label>
                <input type="date" name="end_date" id="end_date" required>

                <label for="insurance_amount">Сумма страхования:</label>
                <input type="text" name="insurance_amount" id="insurance_amount" required>

                <button type="submit" class="btn">Оформить страховку</button>
            </form>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <?php $conn->close(); ?>
</body>
</html>
