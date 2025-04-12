<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Подключение к БД
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
    $username = $_POST['username'];

    // Обновление имени пользователя
    if (!empty($username)) {
        $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
        $stmt->execute([$username, $_SESSION['user_id']]);
    }

    // Загрузка аватарки в БД
    if (!empty($_FILES['avatar']['tmp_name'])) {
        $avatarData = file_get_contents($_FILES['avatar']['tmp_name']); // Читаем изображение в бинарный формат

        // Проверка размера (не больше 2MB)
        if ($_FILES['avatar']['size'] > 2 * 1024 * 1024) {
            die("Ошибка: изображение слишком большое. Максимальный размер — 2MB.");
        }

        // Проверка типа (только PNG и JPEG)
        $allowedTypes = ['image/jpeg', 'image/png'];
        if (!in_array($_FILES['avatar']['type'], $allowedTypes)) {
            die("Ошибка: допустимы только файлы JPEG и PNG.");
        }

        // Сохранение аватарки в БД
        $stmt = $pdo->prepare("UPDATE users SET avatar = ? WHERE id = ?");
        $stmt->execute([$avatarData, $_SESSION['user_id']]);
    }

    header("Location: vhod.php");
    exit();
}

// Получение данных пользователя
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование профиля</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Редактирование профиля</h1>

        <form method="POST" enctype="multipart/form-data">
            <label for="username">Имя пользователя:</label>
            <input type="text" id="username" name="username" value="<?= htmlspecialchars($userData['username']) ?>">

            <label for="avatar">Загрузить новую аватарку:</label>
            <input type="file" id="avatar" name="avatar">

            <button type="submit" class="btn">Сохранить</button>
        </form>

        <a href="vhod.php" class="btn">Назад</a>
    </div>
</body>
</html>
