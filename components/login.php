<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Проверяем пользователя в базе данных
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Сохраняем данные пользователя в сессию
        $_SESSION['user'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];

        // Перенаправляем в зависимости от роли
        if ($user['role'] === 'admin') {
            header("Location: vhod.php"); // Страница админа
        } else {
            header("Location: vhod.php"); // Главная страница для пользователей
        }
        exit();
    } else {
        echo "<script>alert('Неверные учетные данные');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="stylesheet" href="vhod.css">
</head>
<body>
    <div class="form-container">
        <form action="login.php" method="POST">
            <h2>Авторизация</h2>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Войти</button>
            <div class="register-link">
                <p>Еще не зарегистрированы? <a href="reg.php">Зарегистрироваться</a></p>
            </div>
        </form>
    </div>
</body>
</html>
