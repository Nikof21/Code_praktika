<?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        session_start();
        require("db.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="akk.css">
    <style>
        .empty-cart-message {
            color: white;
        }

        .cart-item {
            color: white;
        }
        @font-face {
	font-family: 'Oswald-light'; 
	src: url(Font/static/Oswald-Light.ttf); 
}
.p10{
    font-size: 100px;
    margin-top: -50px;
}

    </style>
    <title>КОРЗИНА</title>
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
        <h2>КОРЗИНА</h2>

        <?php
        // Проверяем, существует ли индекс 'user' в массиве $_SESSION
        if (isset($_SESSION['user'])) {
            $username = $_SESSION['user']['username'];

            // Получаем заказы для пользователя из базы данных
            $orders = getOrdersForUser($username);

            if ($orders) {
                // Выводим товары из корзины
                foreach ($orders as $order) {
                    echo "<p>{$order['product_name']} - {$order['price']} руб.</p>";
                }
            } else {
                echo '<p style="color: white; 
                font-family: "Oswald-light"; ">Корзина пуста </p>';
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['addToCart'])) {
                    $productId = $_POST['productId'];
                    $productName = $_POST['productName'];

                    // Предполагаю, что у вас есть данные о цене товара
                    $price = getPriceForProduct($productId);

                    // Сохраняем информацию о заказе в базе данных
                    $stmt = $db->prepare("INSERT INTO orders (username, product_name, price) VALUES (:username, :product_name, :price)");
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':product_name', $productName);
                    $stmt->bindParam(':price', $price);
                    
                    if ($stmt->execute()) {
                        header('Content-Type: application/json');
                        echo json_encode(['message' => '<p class="p10" style="color: white; 
                        font-family: "Oswald-light";">Товар добавлен в корзину</p>']);
                    } else {
                        header('Content-Type: application/json');
                        echo json_encode(['message' => '<p class="p10" style="color: white; 
                        font-family: "Oswald-light";">Ошибка при добавлении товара в корзину</p>']);
                    }
                }
            }
        } else {
            // Индекс 'user' не существует в массиве $_SESSION
            echo '<p class="p10" style="color: white; 
            font-family: "Oswald-light";">Пользователь не авторизован!</p>'; // Или выполните другие действия в случае отсутствия пользователя
        }
        ?>
        <form action="confirm_order.php" method="post">
        <button class="buy-button" style="
         background-color: #262626;
                        font-family: 'Oswald-light';
                        border: 0px solid;
                        color: white;
                        font-size: 50px;
                        font-weight: 600;
                        padding-bottom: 10px;
                        "  type="submit" class="checkout-button">Оформить заказ</button>
        </form>
    </div>
</body>
</html>
