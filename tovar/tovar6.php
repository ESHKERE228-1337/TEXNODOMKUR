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

// Получение товаров из таблицы catalog для категории "Смартфоны"
$stmt = $pdo->query("SELECT id, name, price, images FROM catalog WHERE category = 'Смартфоны'");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Техно-Дом | Смартфоны</title>
    <link rel="stylesheet" href="styles.css">
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
    <main>
        <div class="container">
            <!-- Фильтры -->
            <aside class="filters">
                <h2>Фильтры</h2>
                <div class="filter-section">
                    <h3>Цена</h3>
                    <input type="range" min="0" max="100000" value="50000" class="price-slider">
                    <p>Максимальная цена: <span id="price-value">50000</span> ₽</p>
                </div>
                <div class="filter-section">
                    <h3>Бренд</h3>
                    <label><input type="checkbox" name="brand" value="Apple"> Apple</label>
                    <label><input type="checkbox" name="brand" value="Samsung"> Samsung</label>
                    <label><input type="checkbox" name="brand" value="Xiaomi"> Xiaomi</label>
                    <label><input type="checkbox" name="brand" value="Huawei"> Huawei</label>
                    <label><input type="checkbox" name="brand" value="Realme"> Realme</label>
                </div>
                <div class="filter-section">
                    <h3>Оперативная память</h3>
                    <label><input type="checkbox" name="ram" value="4 ГБ"> 4 ГБ</label>
                    <label><input type="checkbox" name="ram" value="6 ГБ"> 6 ГБ</label>
                    <label><input type="checkbox" name="ram" value="8 ГБ"> 8 ГБ</label>
                    <label><input type="checkbox" name="ram" value="12 ГБ"> 12 ГБ</label>
                </div>
                <div class="filter-section">
                    <h3>Объем памяти</h3>
                    <label><input type="checkbox" name="storage" value="64 ГБ"> 64 ГБ</label>
                    <label><input type="checkbox" name="storage" value="128 ГБ"> 128 ГБ</label>
                    <label><input type="checkbox" name="storage" value="256 ГБ"> 256 ГБ</label>
                    <label><input type="checkbox" name="storage" value="512 ГБ"> 512 ГБ</label>
                </div>
                <div class="filter-section">
                    <h3>Диагональ экрана</h3>
                    <label><input type="checkbox" name="screen_size" value="До 5.5 дюймов"> До 5.5 дюймов</label>
                    <label><input type="checkbox" name="screen_size" value="5.5 - 6.5 дюймов"> 5.5 - 6.5 дюймов</label>
                    <label><input type="checkbox" name="screen_size" value="Более 6.5 дюймов"> Более 6.5 дюймов</label>
                </div>
                <div class="filter-section">
                    <h3>Тип экрана</h3>
                    <label><input type="checkbox" name="screen_type" value="AMOLED"> AMOLED</label>
                    <label><input type="checkbox" name="screen_type" value="IPS"> IPS</label>
                    <label><input type="checkbox" name="screen_type" value="OLED"> OLED</label>
                </div>
            </aside>

            <!-- Блоки с товарами -->
            <section class="products">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="data:image/jpeg;base64,<?= base64_encode($product['images']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="price"><?= number_format($product['price'], 0, '', ' ') ?> ₽</p>
                        <a href="korzina.php?id=<?= $product['id'] ?>" class="buy-button">Купить</a>
                        <button class="favorite-button" data-product-id="<?= $product['id'] ?>">В избранное</button>
                    </div>
                <?php endforeach; ?>
            </section>
        </div>
    </main>

    <!-- Модальное окно -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <img src="" alt="Товар" class="modal-image">
            <h2 class="modal-title"></h2>
            <p class="modal-price"></p>
            <p class="modal-description"></p>
            <p class="modal-delivery">Доставка: бесплатно</p>
            <button class="modal-buy-button">Купить</button>
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

    <script src="script.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.favorite-button').on('click', function() {
                var productId = $(this).data('product-id');
                var userId = 1; // Замените на реальный ID пользователя, например, из сессии

                $.ajax({
                    url: 'add_to_favorites.php',
                    type: 'POST',
                    data: {
                        user_id: userId,
                        product_id: productId
                    },
                    success: function(response) {
                        alert('Товар добавлен в избранное!');
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