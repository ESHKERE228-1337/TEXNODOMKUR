<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
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

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cardNumber = str_replace(' ', '', $_POST['card_number']); // Удаляем пробелы
    $cardHolder = $_POST['card_holder'];
    $expiryDate = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];

    // Валидация данных
    if (strlen($cardNumber) !== 16 || !ctype_digit($cardNumber)) {
        die("Номер карты должен состоять из 16 цифр.");
    }
    if (strlen($cvv) !== 3 || !ctype_digit($cvv)) {
        die("CVV должен состоять из 3 цифр.");
    }
    if (strlen($expiryDate) !== 5 || !preg_match('/^\d{2}\/\d{2}$/', $expiryDate)) {
        die("Срок действия карты должен быть в формате MM/YY.");
    }

    // Добавление карты в базу данных
    $stmt = $pdo->prepare("INSERT INTO cards (user_id, card_number, card_holder, expiry_date, cvv) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $cardNumber, $cardHolder, $expiryDate, $cvv]);

    header("Location: vhod.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Привязать карту</title>
    <link rel="stylesheet" href="vhod.css">
</head>
<body>
    <div class="container">
        <h1>Привязать карту</h1>
        <form method="POST">
            <label for="card_number">Номер карты:</label>
            <input type="text" id="card_number" name="card_number" placeholder="0000 0000 0000 0000" required>

            <label for="card_holder">Держатель карты:</label>
            <input type="text" id="card_holder" name="card_holder" placeholder="IVAN IVANOV" required>

            <label for="expiry_date">Срок действия:</label>
            <input type="text" id="expiry_date" name="expiry_date" placeholder="MM/YY" required>

            <label for="cvv">CVV:</label>
            <input type="text" id="cvv" name="cvv" placeholder="123" required>

            <button type="submit" class="btn">Привязать</button>
        </form>
        <a href="index.php" class="btn">Назад</a>
    </div>
</body>
</html>