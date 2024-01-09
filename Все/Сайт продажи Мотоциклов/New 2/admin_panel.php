<?php
session_start();
require("db.php");

// Проверяем, является ли пользователь администратором
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
    // Получаем все заказы
    $orders = getAllOrders();

    // Получаем все товары в каталоге
    $products = getAllProducts();
} else {
    // Перенаправляем не администраторов на страницу входа
    header("Location: login.php");
    exit();
}

// Обработка обновления статуса заказа
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateStatus'])) {
    $orderId = $_POST['orderId'];
    $newStatus = $_POST['newStatus'];

    if (updateOrderStatus($orderId, $newStatus)) {
        echo "<script>alert('Статус заказа успешно обновлен!');</script>";
        header("Refresh:0");
    } else {
        echo "<script>alert('Ошибка при обновлении статуса заказа.');</script>";
    }
}

// Обработка обновления цены товара
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updatePrice'])) {
    $productId = $_POST['productId'];
    $newPrice = $_POST['newPrice'];

    if (updateProductPrice($productId, $newPrice)) {
        echo "<script>alert('Цена товара успешно обновлена!');</script>";
        header("Refresh:0");
    } else {
        echo "<script>alert('Ошибка при обновлении цены товара.');</script>";
    }
}

function getAllOrders() {
    global $db;

    $stmt = $db->prepare("SELECT * FROM orders");
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllProducts() {
    global $db;

    $stmt = $db->prepare("SELECT * FROM products");
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateOrderStatus($orderId, $newStatus) {
    global $db;

    $stmt = $db->prepare("UPDATE orders SET status = :status WHERE id = :id");
    $stmt->bindParam(':status', $newStatus);
    $stmt->bindParam(':id', $orderId);

    return $stmt->execute();
}

function updateProductPrice($productId, $newPrice) {
    global $db;

    $stmt = $db->prepare("UPDATE products SET price = :price WHERE id = :id");
    $stmt->bindParam(':price', $newPrice);
    $stmt->bindParam(':id', $productId);

    return $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin_panel.css">
    <title>Админ-панель</title>
</head>
<body>
    <h2>Админ-панель</h2>

    <!-- Вывод заказов -->
    <h3>Заказы</h3>
    <?php
    foreach ($orders as $order) {
        echo "<p>{$order['username']} - {$order['product_name']} - {$order['price']} руб. - Статус: {$order['status']}</p>";
        echo "<form action='#' method='post'>
                  <input type='hidden' name='orderId' value='{$order['id']}'>
                  <label>Изменить статус: 
                      <input type='text' name='newStatus' required>
                  </label>
                  <button type='submit' name='updateStatus'>Обновить статус</button>
              </form>";
    }
    ?>

    <!-- Вывод товаров в каталоге -->
    <h3>Каталог товаров</h3>
    <?php
    foreach ($products as $product) {
        echo "<p>{$product['name']} - {$product['price']} руб.</p>";
        echo "<form action='#' method='post'>
                  <input type='hidden' name='productId' value='{$product['id']}'>
                  <label>Изменить цену: 
                      <input type='text' name='newPrice' required>
                  </label>
                  <button type='submit' name='updatePrice'>Обновить цену</button>
              </form>";
    }
    ?>
    <button name='glav'><a href="glav.html" class='a_glav'>На главную</a></button>
</body>
</html>
