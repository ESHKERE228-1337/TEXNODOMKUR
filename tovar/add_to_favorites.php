<?php
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

$userId = $_POST['user_id'];
$productId = $_POST['product_id'];

$stmt = $pdo->prepare("INSERT INTO favorites (user_id, product_id) VALUES (:user_id, :product_id)");
$stmt->execute(['user_id' => $userId, 'product_id' => $productId]);

echo 'Товар успешно добавлен в избранное!';

?>
