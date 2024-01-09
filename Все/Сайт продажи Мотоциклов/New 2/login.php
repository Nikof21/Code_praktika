<?php
session_start();
require("db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);

    if ($stmt->execute()) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;

            // Проверяем, является ли пользователь администратором
            if ($user['role'] === 'admin') {
                // Выводим сообщение об успешном входе и перенаправляем с использованием JavaScript
                echo "<script>alert('Вход успешен!'); location.href='admin_panel.php';</script>";
                exit();
            } else {
                echo "<script>alert('Вход успешен!');</script>";
            }
        } else {
            echo "<script>alert('Неверный логин или пароль.');</script>";
        }
    } else {
        echo "Ошибка выполнения запроса: " . print_r($stmt->errorInfo(), true);
    }
}
?>
<link rel="stylesheet" href="login.css">
<style>
    @font-face {
	font-family: 'Oswald-light'; 
	src: url(Font/static/Oswald-Light.ttf); 
}
    label{
        font-family: 'Oswald-light'; 
        color: white;
        font-size:60px;
    }
    div{
        margin-top: -100px;
    }
    button[name="login"]{
    margin-top: 50px;
    font-family: 'Oswald-light'; 
    font-size: 100px;
    font-weight: 100;
    color: #B38F58;
    background-color: #262626;
    border: 0px;
    transition: 0.3s;
}
</style>
<div>
<h2>АДМИН ПАНЕЛЬ</h2>
<form action="#" method="post">
    <label>Логин: <input type="text" name="username"></label>
    <br>
    <label>Пароль: <input type="password" name="password"></label>
    <br>
    <button name="login">Войти</button>
</form>
</div>
<?php
// Проверяем, авторизован ли пользователь
if (isset($_SESSION['user'])) {
    // Проверяем, является ли пользователь администратором
    if ($_SESSION['user']['role'] === 'admin') {
        // Отображаем выпадающий список для входа в админ панель только если пользователь администратор
        echo '<form action="admin_panel.php" method="post">
                  <label>Выберите действие: 
                      <select name="admin_action">
                          <option value="view_orders">Просмотр заказов</option>
                          <option value="add_product">Добавить товар</option>
                      </select>
                  </label>
                  <button type="submit">Перейти</button>
              </form>';
    }
}
?>