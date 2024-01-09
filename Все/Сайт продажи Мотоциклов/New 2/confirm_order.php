<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Подтверждение заказа</title>
</head>
<body>
    <h2>Заказ успешно оформлен!</h2>
    <p>Благодарим вас за покупку. Наш менеджер свяжется с вами для подтверждения заказа.</p>
    <a href="glav.html">
        <button>На главную страницу</button>
    </a>
</body>
</html>

<?php
// Очистка корзины пользователя после оформления заказа
if (isset($_SESSION['user'])) {
    $username = $_SESSION['user']['username'];
    clearCart($username);
}

function clearCart($username) {
    global $db;

    $stmt = $db->prepare("DELETE FROM orders WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
}
?>
