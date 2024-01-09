<?php
session_start();
require("db.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="akk.css">
    <title>Личный кабинет</title>
</head>
<body>
    <div class="container_menu">
        <li class="ml">
            <ul><a class="a1" href="onas.html">О НАС</a></ul>  
            <ul><a class="a1" href="katalog.html">КАТАЛОГ</a></ul>
            <ul><a class="a1" href="novosti.html">НОВОСТИ</a></ul>
            <ul><a class="a1" href="kontakt.html">КОНТАКТЫ</a></ul>
            <ul><a class="a1" href="akk.php">АККАУНТ</a></ul>
            <ul><a class="a1" href="korzina.php">КОРЗИНА</a></ul>
        </li>
    </div>
    <div class="container_akk">
        <h2>ЛИЧНЫЙ КАБИНЕТ</h2>
        <?php


// Проверка сессии на наличие пользователя
if (isset($_SESSION['user'])) {
    echo "<style>
    @font-face {
        font-family: 'Oswald';
        src: url('Font/static/Oswald-Regular.ttf') format('truetype');
    }

    p.welcome-message {
        font-size: 50px;
        font-family: 'Oswald', sans-serif;
        line-height: 1;
        color: #B38F58;
        font-weight: 100;
    }
    .welcome-message2 {
        font-size: 50px;
        font-family: 'Oswald', sans-serif;
        line-height: 1;
        color: #B38F58;
        font-weight: 100;
        text-decoration: none;
        color: white;
    }
    .welcome-message3 {
        font-size: 50px;
        font-family: 'Oswald', sans-serif;
        line-height: 1;
        color: #B38F58;
        font-weight: 100;
        text-decoration: none;
        color: white;
    }
    a{
        text-decoration: none;
        color: white;
        transition: 0.5s;
    }
    a:hover{
        text-decoration: none;
        color: white;
        transition: 0.5s;
    }
    </style>";

    echo "<p class='welcome-message'>ДОБРО ПОЖАЛОВАТЬ, {$_SESSION['user']['username']}!</p>";
    echo "<p class='welcome-message2'><a class='welcome-message3' href='logout.php'>Выйти &#x200b;</a></p>";
    echo "<p class='welcome-message3'><a class='welcome-message3' href='glav.html'>Продолжить</a></p>";

} else {
    // Обработка входа и регистрации
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['submit'])) {
            // Вход
            $username = $_POST['username'];
            $password = $_POST['password'];

            $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);

            if ($stmt->execute()) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user'] = $user;
                    echo "<script>alert('Вход успешен, {$_SESSION['user']['username']}!');</script>";
                    echo "<script>window.location.href = 'glav.html';</script>";
                    exit();
                } else {
                    echo "<script>alert('Неверный логин или пароль.');</script>";
                }
            } else {
                echo "Ошибка выполнения запроса: " . print_r($stmt->errorInfo(), true);
            }
        } elseif (isset($_POST['register'])) {
            // Регистрация
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);

            if ($stmt->execute()) {
                echo "<p>Регистрация успешна, {$_SESSION['user']['username']}!</p>";
                echo '<p><a href="login.php">Войти</a></p>';
            } else {
                echo "Ошибка регистрации: " . print_r($stmt->errorInfo(), true);
            }
        }
    }
}
?>


        <div class="container_reg2">
            <form action="#" method="POST">
                <b class="a3">ЛОГИН</b><input type="text" name="username" placeholder="______________">
                <br>
                <b class="a3">ПАРОЛЬ</b><input type="password" name="password" placeholder="____________">
                <br>
                <input type="submit" name="submit" value="ВОЙТИ">
                <a class="a3" href="register.php">РЕГИСТРАЦИЯ</a>
                <br>
                <a class="a3" href="admin_panel.php">Админ панель</a>
            </form>
        </div>
    </div>
</body>
</html>

