<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Проверка, существует ли email в базе данных
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Если email уже существует, выводим сообщение об ошибке
        echo "<div class='message error' id='message'>Ошибка: Этот email уже зарегистрирован.</div>";
    } else {
        // Если email не существует, регистрируем пользователя
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$username, $email, $password]);
            echo "<div class='message success' id='message'>Регистрация успешна! <a href='login.php'>Войдите</a>.</div>";
        } catch (PDOException $e) {
            echo "<div class='message error' id='message'>Ошибка регистрации: " . $e->getMessage() . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="vhod.css">
</head>
<body>
    <div class="form-container">
        <form action="reg.php" method="POST">
            <h2>Регистрация</h2>
            <input type="text" name="username" placeholder="Имя пользователя" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Пароль" required>
            <button type="submit">Зарегистрироваться</button>
        </form>
    </div>

    <script>
        // Плавное появление сообщения
        const message = document.getElementById('message');
        if (message) {
            setTimeout(() => {
                message.classList.add('show');
            }, 100); // Небольшая задержка для плавного появления
        }
    </script>
</body>
</html>
