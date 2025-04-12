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

// Получение данных пользователя
$stmt = $pdo->prepare("SELECT username, avatar, created_at FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

// Кодирование аватарки в Base64
$avatarSrc = !empty($userData['avatar']) 
    ? 'data:image/jpeg;base64,' . base64_encode($userData['avatar']) 
    : 'default-avatar.png'; // Если нет аватарки, показать заглушку

// Получение привязанных карт
$cardsStmt = $pdo->prepare("SELECT id, card_number, card_holder, expiry_date FROM cards WHERE user_id = ?");
$cardsStmt->execute([$_SESSION['user_id']]);
$cards = $cardsStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <div class="container">
        <h1>Добро пожаловать, <?= htmlspecialchars($userData['username'] ?? 'Гость') ?>!</h1>
        <div class="profile">
            <!-- Отображение аватарки -->
            <img src="<?= $avatarSrc ?>" alt="Аватарка" class="avatar">
            
            <p>Дата регистрации: <?= htmlspecialchars($userData['created_at'] ?? 'неизвестно') ?></p>
            <div class="buttons">
                <a href="edit_profile.php" class="btn">Редактировать профиль</a>
                <a href="logout.php" class="btn">Выйти</a>
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <a href="admin/admin.php" class="btn admin-btn">Админ панель</a>
                    <a href="admin/adminUsers.php" class="btn admin-btn">Список пользователей</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Секция привязанных карт -->
        <div class="cards-section">
            <h2>Привязанные карты</h2>
            <?php if (count($cards) > 0): ?>
                <ul class="cards-list">
                    <?php foreach ($cards as $card): ?>
                        <li class="card-item">
                            <p>Карта: **** **** **** <?= substr($card['card_number'], -4) ?></p>
                            <p>Держатель: <?= htmlspecialchars($card['card_holder']) ?></p>
                            <p>Срок действия: <?= htmlspecialchars($card['expiry_date']) ?></p>
                            <a href="remove_card.php?id=<?= $card['id'] ?>" class="btn remove-btn">Удалить</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>У вас нет привязанных карт.</p>
            <?php endif; ?>
            <a href="add_card.php" class="btn add-card-btn">Привязать карту</a>
        </div>
    </div>
</body>
</html>
