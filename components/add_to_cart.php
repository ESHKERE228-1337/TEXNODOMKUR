<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    die("Вы должны войти в систему, чтобы добавить товар в корзину.");
}

// Получаем ID пользователя из сессии (предполагается, что в сессии хранится ID)
$user_id = $_SESSION['user_id'];
$product_name = $_POST['product_name'];
$product_price = $_POST['product_price'];

// Добавляем товар в корзину
$stmt = $pdo->prepare("INSERT INTO cart (user_id, product_name, product_price) VALUES (?, ?, ?)");
try {
    $stmt->execute([$user_id, $product_name, $product_price]);
    header("Location: ../cart.php");
} catch (PDOException $e) {
    echo "Ошибка добавления в корзину: " . $e->getMessage();
}
?>
