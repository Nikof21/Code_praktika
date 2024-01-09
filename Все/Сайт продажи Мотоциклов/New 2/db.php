<?php
try {
    $db = new PDO("mysql:host=localhost;dbname=m_magaz", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Ошибка подключения к базе данных: " . $e->getMessage();
    exit();
}

// Функция для получения цены товара по его имени
function getPriceForProduct($productName) {
    global $db;

    $stmt = $db->prepare("SELECT price FROM products WHERE name = :name");
    $stmt->bindParam(':name', $productName);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ? $result['price'] : null;
}

// Функция для получения заказов пользователя
function getOrdersForUser($username) {
    global $db;

    $stmt = $db->prepare("SELECT * FROM orders WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Функция для получения общей суммы заказов пользователя
function getTotalPriceForUser($username) {
    global $db;

    $stmt = $db->prepare("SELECT SUM(price) AS total_price FROM orders WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['total_price'];
}

// Функция для очистки корзины пользователя
function clearCart($username) {
    global $db;

    $stmt = $db->prepare("DELETE FROM orders WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
}
?>
