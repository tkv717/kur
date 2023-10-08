<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контакты - SafeGuard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <section class="hero">
        <div class="container">
            <h1>Контакты</h1>
            <p>Свяжитесь с нами для получения дополнительной информации или консультации.</p>
        </div>
    </section>

    <section class="contact">
        <div class="container">
            <div class="contact-details">
                <h2>Наши контактные данные</h2>
                <p>Адрес: ул. Примерная, 123, г. Примерово</p>
                <p>Телефон: +7 123 456-7890</p>
                <p>Email: info@example.com</p>
            </div>
            <div class="contact-form">
                <h2>Свяжитесь с нами</h2>
                <form action="#" method="POST">
                    <input type="text" name="name" placeholder="Ваше имя" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <textarea name="message" placeholder="Ваше сообщение" required></textarea>
                    <button type="submit" class="btn">Отправить</button>
                </form>
            </div>
        </div>
    </section>
    <?php include 'footer.php'; ?>
</body>
</html>
