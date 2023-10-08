<?php
session_start();

// Подключение к базе данных
$host = "localhost";
$username = "root";
$password = "";
$dbname = "tkvbd";

// Установка соединения с базой данных
$conn = new mysqli($host, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die('Ошибка подключения к базе данных: ' . $conn->connect_error);
}

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    // Если пользователь не авторизован, перенаправляем на страницу входа
    header('Location: login.php');
    exit();
}

// Получение имени пользователя из сессии
$username = $_SESSION['login'];

// Получение информации о пользователе
$userInfo = getUserInfo($conn, $username);

// Функция для получения информации о пользователе
function getUserInfo($conn, $username) {
    $sql = "SELECT * FROM users WHERE login = '$username'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// Получение истории страховок пользователя
$insuranceHistory = getInsuranceHistory($conn, $username);

// Функция для получения истории страховок пользователя
function getInsuranceHistory($conn, $username) {
    $sql = "SELECT * FROM insurance_history WHERE user_id = (SELECT id FROM users WHERE login = '$username')";
    $result = $conn->query($sql);
    $insuranceHistory = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $insuranceHistory[] = $row;
        }
    }
    return $insuranceHistory;
}

// Обработка обновления данных пользователя
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $field = $_POST['field'];
    $value = $_POST['value'];
    $updateResult = updateUserInfo($conn, $username, $field, $value);
    if ($updateResult) {
        echo 'success';
        exit();
    } else {
        echo 'error';
        exit();
    }
}

// Функция для обновления данных пользователя
function updateUserInfo($conn, $username, $field, $value) {
    $sql = "UPDATE users SET $field = '$value' WHERE login = '$username'";
    $result = $conn->query($sql);
    return $result;
}

// Закрытие соединения с базой данных
$conn->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"> <!-- Подключение библиотеки иконок Font Awesome -->
    <style>
        .edit-icon {
            color: #999;
            cursor: pointer;
            margin-left: 5px;
        }

        .edit-form {
            display: none;
        }

        .card {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }

        .card-title {
            font-weight: bold;
        }

        .card-status {
            margin-top: 5px;
        }

        .card-status.expired {
            color: red;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="dashboard">
        <div class="container">
            <h2>Личный кабинет</h2>
            <p>Добро пожаловать, <?php echo $userInfo['name']; ?>!</p>
            <p>Здесь вы можете увидеть информацию о своем аккаунте и другую полезную информацию.</p>
            <h3>Информация о пользователе</h3>
            <ul>
                <li><strong>Имя:</strong> <span class="value"><?php echo $userInfo['name']; ?></span>
                    <i class="fas fa-pencil-alt edit-icon"></i> <!-- Иконка редактирования -->
                    <form class="edit-form" action="dashboard.php" method="post">
                        <input type="hidden" name="field" value="name">
                        <input type="text" name="value" placeholder="Введите новое имя" pattern="[а-яА-ЯёЁ\s]+" title="Имя может содержать только русские буквы" required>
                        <button type="submit">Сохранить</button>
                    </form>
                </li>
                <li><strong>Email:</strong> <span class="value"><?php echo $userInfo['email']; ?></span>
                    <i class="fas fa-pencil-alt edit-icon"></i> <!-- Иконка редактирования -->
                    <form class="edit-form" action="dashboard.php" method="post">
                        <input type="hidden" name="field" value="email">
                        <input type="email" name="value" placeholder="Введите новый email" required>
                        <button type="submit">Сохранить</button>
                    </form>
                </li>
                <li><strong>Номер телефона:</strong> <span class="value"><?php echo $userInfo['phone_number']; ?></span>
                    <i class="fas fa-pencil-alt edit-icon"></i> <!-- Иконка редактирования -->
                    <form class="edit-form" action="dashboard.php" method="post">
                        <input type="hidden" name="field" value="phone_number">
                        <input type="tel" name="value" placeholder="Введите новый номер телефона" pattern="\+7\d{10}" title="Номер телефона должен быть в формате +79123456789" required>
                        <button type="submit">Сохранить</button>
                    </form>
                </li>
                <li><strong>Дата рождения:</strong> <span class="value"><?php echo $userInfo['date_of_birth']; ?></span>
                    <i class="fas fa-pencil-alt edit-icon"></i> <!-- Иконка редактирования -->
                    <form class="edit-form" action="dashboard.php" method="post">
                        <input type="hidden" name="field" value="date_of_birth">
                        <input type="date" name="value" required>
                        <button type="submit">Сохранить</button>
                    </form>
                </li>
            </ul>
            <h3>История страховок</h3>
            <?php if (!empty($insuranceHistory)) : ?>
                <div class="card-container">
                    <?php foreach ($insuranceHistory as $insurance) : ?>
                        <div class="card">
                            <div class="card-title"><?php echo $insurance['insurance_type']; ?></div>
                            <div class="card-status <?php echo ($insurance['status'] === 'Просрочено') ? 'expired' : ''; ?>">
                                <?php echo $insurance['status']; ?>
                            </div>
                            <div><strong>Дата начала:</strong> <?php echo $insurance['start_date']; ?></div>
                            <div><strong>Дата окончания:</strong> <?php echo $insurance['end_date']; ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p>У вас пока нет истории страховок.</p>
            <?php endif; ?>
            <a href="insurance.php" class="btn">Оформить страховку</a>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> <!-- Подключение библиотеки jQuery -->
    <script>
        // Получение элементов
        const editIcons = document.querySelectorAll('.edit-icon');
        const editForms = document.querySelectorAll('.edit-form');

        // Добавление обработчика события на иконки редактирования
        editIcons.forEach((editIcon, index) => {
            editIcon.addEventListener('click', () => {
                toggleEditForm(index); // Переключение состояния формы редактирования
            });
        });

        // Функция для переключения состояния формы редактирования
        function toggleEditForm(index) {
            editForms.forEach((editForm, formIndex) => {
                if (index === formIndex) {
                    editForm.style.display = editForm.style.display === 'none' ? 'block' : 'none';
                } else {
                    editForm.style.display = 'none';
                }
            });
        }

        // Обработка отправки данных формы редактирования
        $('.edit-form').submit(function (e) {
            e.preventDefault();
            const form = $(this);
            const value = form.find('input[name="value"]').val();
            const field = form.find('input[name="field"]').val();

            $.ajax({
                url: form.attr('action'),
                method: 'POST',
                data: {
                    field: field,
                    value: value
                },
                success: function (response) {
                    if (response === 'success') {
                        form.siblings('.value').text(value);
                        toggleEditForm();
                    } else {
                        alert('Произошла ошибка при обновлении данных.');
                    }
                },
                error: function () {
                    alert('Произошла ошибка при отправке запроса.');
                }
            });
        });
    </script>
    <?php include 'footer.php'; ?>
</body>
</html>
