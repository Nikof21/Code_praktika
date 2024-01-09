<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = json_decode(file_get_contents('php://input'), true);

    if (isset($requestData['addToCart'])) {
        $productName = $requestData['productName'];

        // Получаем цену товара по его имени
        $price = getPriceForProduct($productName);

        if ($price !== null) {
            // Сохраняем информацию о заказе в базе данных
            $username = isset($_SESSION['user']['username']) ? $_SESSION['user']['username'] : 'guest';

            $db = new PDO("mysql:host=localhost;dbname=m_magaz", "root", "");
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $db->prepare("INSERT INTO orders (username, product_name, price) VALUES (:username, :product_name, :price)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':product_name', $productName);
            $stmt->bindParam(':price', $price);

            if ($stmt->execute()) {
                // Успешное добавление в корзину
                echo json_encode(['message' => 'Товар добавлен в корзину']);
            } else {
                // Ошибка при добавлении в корзину
                http_response_code(500); // Устанавливаем код состояния 500 (внутренняя ошибка сервера)
                echo json_encode(['message' => 'Ошибка при добавлении товара в корзину']);
            }
        } else {
            // Ошибка: цена не найдена
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Ошибка: цена товара не найдена']);
        }
    }
}

function getPriceForProduct($productName) {
    $db = new PDO("mysql:host=localhost;dbname=m_magaz", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $db->prepare("SELECT price FROM products WHERE name = :name");
    $stmt->bindParam(':name', $productName);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ? $result['price'] : null;
}
?>
