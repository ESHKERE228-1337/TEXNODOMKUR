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

// Получение товаров из таблицы catalog для категории "Встраиваемая техника"
$stmt = $pdo->query("SELECT id, name, price, images FROM catalog WHERE category = 'Встраиваемая техника'");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Техно-Дом | Встраиваемая техника</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Общие стили */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #2c3e50;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .container {
            display: flex;
            gap: 20px;
            padding: 20px;
        }

        .filters {
            width: 250px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .filter-section {
            margin-bottom: 20px;
        }

        .filter-section h3 {
            margin-bottom: 10px;
            font-size: 18px;
        }

        .products {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .product-card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }

        .product-card h3 {
            margin: 15px 0 10px;
            font-size: 20px;
        }

        .price {
            font-size: 18px;
            color: #007bff;
            margin-bottom: 15px;
        }

        .buy-button {
            padding: 10px 20px;
            background: green;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .buy-button:hover {
            background: darkgreen;
        }

        .favorite-button {
            padding: 10px 20px;
            background: red;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 10px;
        }

        .favorite-button:hover {
            background: darkred;
        }

        footer {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: auto;
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
            <a href="korzina.php">
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
                    <label><input type="checkbox" name="brand" value="Bosch"> Bosch</label>
                    <label><input type="checkbox" name="brand" value="Samsung"> Samsung</label>
                    <label><input type="checkbox" name="brand" value="LG"> LG</label>
                </div>
                <div class="filter-section">
                    <h3>Тип техники</h3>
                    <label><input type="checkbox" name="type" value="Холодильник"> Холодильник</label>
                    <label><input type="checkbox" name="type" value="Духовка"> Духовка</label>
                    <label><input type="checkbox" name="type" value="Варочная панель"> Варочная панель</label>
                </div>
            </aside>

            <!-- Блоки с товарами -->
            <section class="products">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="data:image/jpeg;base64,<?= base64_encode($product['images']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p class="price"><?= number_format($product['price'], 0, '', ' ') ?> ₽</p>
                        <button class="buy-button" data-product-id="<?= $product['id'] ?>">Купить</button>
                        <button class="favorite-button" data-product-id="<?= $product['id'] ?>">В избранное</button>
                    </div>
                <?php endforeach; ?>
            </section>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 ТехноДом. Все права защищены.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Добавление товара в корзину
            $('.buy-button').on('click', function() {
                var productId = $(this).data('product-id');
                var userId = 1; // Замените на реальный ID пользователя, например, из сессии

                $.ajax({
                    url: 'add_to_cart.php',
                    type: 'POST',
                    data: {
                        product_id: productId,
                        user_id: userId
                    },
                    success: function(response) {
                        alert('Товар добавлен в корзину!');
                    },
                    error: function(xhr, status, error) {
                        alert('Произошла ошибка: ' + error);
                    }
                });
            });

            // Добавление товара в избранное
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