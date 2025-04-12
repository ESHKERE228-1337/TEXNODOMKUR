<?php
session_start();

// Подключение к базе данных
$host = 'localhost';
$db = 'auth_demo';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Получение данных из запроса
$productId = $_POST['product_id'];
$userId = $_SESSION['user_id'] ?? 1; // Если пользователь не авторизован, используем ID 1 (для примера)

// Проверка, есть ли товар уже в корзине
$stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id");
$stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
$existingProduct = $stmt->fetch();

if ($existingProduct) {
    // Если товар уже в корзине, увеличиваем количество
    $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE id = :id");
    $stmt->execute(['id' => $existingProduct['id']]);
} else {
    // Если товара нет в корзине, добавляем его
    $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id) VALUES (:user_id, :product_id)");
    $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
}

echo json_encode(['status' => 'success', 'message' => 'Товар добавлен в корзину']);
?>