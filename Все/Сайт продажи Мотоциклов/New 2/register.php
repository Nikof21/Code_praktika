<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
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
            <ul><a class="a1" href="korzina.html">КОРЗИНА</a></ul>
        </li>
    </div>
    <div class="container_akk">
        <h2>РЕГИСТРАЦИЯ</h2>
        <form action="#" method="post">
    <label class="a3">Логин: <input type="text" name="username"></label>
    <label class="a3">Пароль: <input type="password" name="password"></label>
    <button name="register">Зарегистрироваться</button>
</form>
</body>
</html>

<?php
require("db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = 'user';

    $stmt = $db->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        echo "<script>alert('Регистрация успешна!'); location.href='akk.php'</script>";
    } else {
        echo "Ошибка регистрации: " . print_r($stmt->errorInfo(), true);
    }
}
