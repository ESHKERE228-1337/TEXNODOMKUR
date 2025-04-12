<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Ошибка: Пользователь не авторизован.");
}

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

// Получение данных из формы
$name = $_POST['name'];
$last_name = $_POST['last_name'];
$patronymic = $_POST['patronymic'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$message = $_POST['message'];
$position = $_POST['position'];
$userId = $_SESSION['user_id'];

// Вставка данных в таблицу заявок
$stmt = $pdo->prepare("
    INSERT INTO applications (user_id, name, last_name, patronymic, phone, email, message, position)
    VALUES (:user_id, :name, :last_name, :patronymic, :phone, :email, :message, :position)
");
$stmt->execute([
    'user_id' => $userId,
    'name' => $name,
    'last_name' => $last_name,
    'patronymic' => $patronymic,
    'phone' => $phone,
    'email' => $email,
    'message' => $message,
    'position' => $position,
]);

echo json_encode(['status' => 'success']);
?>