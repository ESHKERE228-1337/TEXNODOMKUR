<?php
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

// Обработка удаления товара из избранного
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'remove') {
    $userId = $_POST['user_id'];
    $productId = $_POST['product_id'];

    // Удаляем товар из избранного
    $stmt = $pdo->prepare("DELETE FROM favorites WHERE user_id = :user_id AND product_id = :product_id");
    $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);

    echo json_encode(['status' => 'success', 'message' => 'Товар удален из избранного']);
    exit;
}

// Получение избранных товаров
$userId = 1; // Замените на реальный ID пользователя
$stmt = $pdo->prepare("
    SELECT c.id, c.name, c.price, c.images 
    FROM favorites f
    JOIN catalog c ON f.product_id = c.id
    WHERE f.user_id = :user_id
");
$stmt->execute(['user_id' => $userId]);
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Избранное</title>
    <style>
        /* Общие стили */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            background-color: #f4f4f4;
        }

        /* Header */
        header {
            background-color: #2c3e50;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        .logo {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }

        .site-name {
            font-size: 24px;
            font-weight: bold;
        }

        .nav-buttons {
            display: flex;
            align-items: center;
        }

        .nav-buttons a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            display: flex;
            align-items: center;
        }

        .nav-buttons a img {
            width: 24px;
            height: 24px;
            margin-right: 5px;
        }

        .nav-buttons a:hover {
            opacity: 0.8;
        }

        /* Основной контент */
        .main-content {
            flex: 1;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
            width: 100%;
        }

        .favorites-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .product-card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }

        .product-name {
            margin: 15px 0 10px;
            font-size: 20px;
            color: #333;
        }

        .product-price {
            font-size: 18px;
            color: #007bff;
            margin-bottom: 15px;
        }

        .no-favorites {
            text-align: center;
            font-size: 18px;
            color: #666;
        }

        .remove-button {
            padding: 10px 20px;
            background: #dc3545;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .remove-button:hover {
            background: #c82333;
        }

        /* Footer */
        .footer {
            background-color: #2c3e50;
            color: #ffffff;
            padding: 40px 20px;
            text-align: center;
            font-family: 'Arial', sans-serif;
            border-top: 4px solid #007bff;
            margin-top: auto; /* Прижимаем футер к низу */
        }

        .footer-links {
            margin-bottom: 20px;
        }

        .footer-links a {
            color: #ffffff;
            text-decoration: none;
            margin: 0 15px;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #007bff;
        }

        .social-icons {
            margin-bottom: 20px;
        }

        .social-icons a {
            margin: 0 10px;
            display: inline-block;
            transition: transform 0.3s ease;
        }

        .social-icons a:hover {
            transform: translateY(-5px);
        }

        .social-icons img {
            width: 32px;
            height: 32px;
        }

        .footer p {
            margin: 0;
            font-size: 14px;
            color: #bdc3c7;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo-container">
            <img src="../img/logo.png" alt="Логотип Техно-Дом" class="logo">
            <span class="site-name">Техно-Дом</span>
        </div>
        <div class="nav-buttons">
            <a href="components/vhod.php">
                <img src="../img/vhod.png" alt="Личный кабинет">
                Личный кабинет
            </a>
            <a href="favorites.php">
                <img src="../img/love.png" alt="Избранное">
                Избранное
            </a>
            <a href="#">
                <img src="../img/korzina.png" alt="Корзина">
                Корзина
            </a>
        </div>
    </header>

    <!-- Основной контент -->
    <div class="main-content">
        <h1>Избранное</h1>
        <div class="favorites-container">
            <?php if (!empty($favorites)): ?>
                <?php foreach ($favorites as $product): ?>
                    <div class="product-card" id="product-<?= $product['id'] ?>">
                        <img src="data:image/jpeg;base64,<?= base64_encode($product['images']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">
                        <h3 class="product-name"><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="product-price"><?= number_format($product['price'], 0, '', ' ') ?> ₽</p>
                        <button class="remove-button" data-product-id="<?= $product['id'] ?>">Удалить из избранного</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-favorites">У вас пока нет избранных товаров.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-links">
            <a href="footer/about.html">О компании</a>
            <a href="footer/stores.html">Магазины</a>
            <a href="footer/vak.html">Вакансии</a>
            <a href="footer/otziv.html">Отзывы</a>
        </div>
        <div class="social-icons">
            <a href="https://vk.com/ваша-страница" target="_blank"><img src="../img/icons8-vk-48.png" alt="VK"></a>
            <a href="https://tg.com/ваша-страница" target="_blank"><img src="../img/icons8-телеграмма-app-48.png" alt="Telegram"></a>
            <a href="https://www.instagram.com/ваша-страница" target="_blank"><img src="../img/icons8-instagram-48.png" alt="Instagram"></a>
        </div>
        <p>&copy; 2024 ТехноДом. Все права защищены.</p>
    </footer>

    <!-- Подключаем jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Обработка нажатия кнопки "Удалить из избранного"
            $('.remove-button').on('click', function() {
                var productId = $(this).data('product-id');
                var userId = <?= $userId ?>; // ID пользователя из PHP
                var productCard = $('#product-' + productId); // Находим карточку товара

                // Отправляем AJAX-запрос
                $.ajax({
                    url: '', // Отправляем на эту же страницу
                    type: 'POST',
                    data: {
                        action: 'remove',
                        user_id: userId,
                        product_id: productId
                    },
                    success: function(response) {
                        var result = JSON.parse(response);
                        if (result.status === 'success') {
                            alert(result.message);
                            productCard.remove(); // Удаляем карточку товара со страницы
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Произошла ошибка: ' + error);
                    }
                });
            });
        });
    </script>
</body>
</html>