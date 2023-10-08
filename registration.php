<?php
// Подключение к базе данных
$host = 'localhost'; // Имя хоста базы данных
$user = 'root'; // Ваше имя пользователя базы данных
$password = ''; // Ваш пароль базы данных
$dbname = 'tkvbd'; // Имя базы данных

// Установка соединения с базой данных
$conn = new mysqli($host, $user, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die('Ошибка подключения к базе данных: ' . $conn->connect_error);
}

// Обработка отправленной формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = mysqli_real_escape_string($conn, $_POST['login']);
    $password = $_POST['password'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $date_of_birth = $_POST['date_of_birth'];

    // Проверка, что поля формы не пустые
    if (!empty($login) && !empty($password) && !empty($name) && !empty($email) && !empty($phone_number) && !empty($date_of_birth)) {
        // Проверка логина на английские символы
        if (!preg_match('/^[a-zA-Z]+$/', $login)) {
            echo 'Логин может содержать только латинские буквы!';
        }
        // Проверка максимальной длины пароля
        elseif (strlen($password) > 12) {
            echo 'Пароль должен содержать не более 12 символов!';
        }
        else {
            // Проверка, что пользователь с таким логином не существует
            $check_query = "SELECT * FROM users WHERE login='$login'";
            $check_result = $conn->query($check_query);
            if ($check_result->num_rows > 0) {
                echo 'Пользователь с таким логином уже существует!';
            } else {
                // Хеширование пароля
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Вставка нового пользователя в базу данных
                $insert_query = "INSERT INTO users (login, password, name, email, phone_number, date_of_birth) VALUES ('$login', '$hashed_password', '$name', '$email', '$phone_number', '$date_of_birth')";
                if ($conn->query($insert_query) === true) {
                    echo 'Регистрация прошла успешно!';
                } else {
                    echo 'Ошибка при регистрации: ' . $conn->error;
                }
            }
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
    <title>Регистрация</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function formatPhoneNumber() {
        var phoneNumberInput = document.forms["registrationForm"]["phone_number"];
        var phoneNumber = phoneNumberInput.value.replace(/\D/g, ''); // Удаление всех нецифровых символов

        if (phoneNumber.length >= 1 && phoneNumber[0] !== '7') {
            phoneNumber = '7' + phoneNumber; // Добавление префикса 7, если его нет
        }

        // Ограничение на количество символов в номере телефона
        phoneNumber = phoneNumber.substr(0, 11);

        var formattedPhoneNumber = '+' + phoneNumber;

        phoneNumberInput.value = formattedPhoneNumber;
    }


        function validateForm() {
            var loginInput = document.forms["registrationForm"]["login"];
            var nameInput = document.forms["registrationForm"]["name"];


            if (!/^[a-zA-Z]+$/.test(loginInput.value)) {
                alert("Логин может содержать только латинские буквы!");
                loginInput.focus();
                return false;
            }

            // Другие проверки...

            return true;
        }
    </script>
</head>
<body>
    <?php include 'header.php'; ?>

    <section class="registration">
        <div class="container">
            <h2>Регистрация</h2>
                <form name="registrationForm" action="" method="POST" onsubmit="return validateForm()" style="display: flex; flex-direction: column; align-items: flex-start;">
                <input type="text" name="login" placeholder="Логин" value="<?php echo isset($_POST['login']) ? $_POST['login'] : ''; ?>" required>
                <input type="password" name="password" placeholder="Пароль" required>
                <input type="text" name="name" placeholder="Ваше имя" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>" required>
                <input type="email" name="email" placeholder="Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
                <input type="text" name="phone_number" placeholder="Номер телефона" oninput="formatPhoneNumber()" value="<?php echo isset($_POST['phone_number']) ? $_POST['phone_number'] : ''; ?>" required>
                <input type="date" name="date_of_birth" placeholder="Дата рождения" value="<?php echo isset($_POST['date_of_birth']) ? $_POST['date_of_birth'] : ''; ?>" required>
                <button type="submit" class="btn">Зарегистрироваться</button>
                <p>Уже зарегистрированы? <a href="login.php">Войти в аккаунт</a></p>
            </form>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>
</html>
