<?php
session_start();
if (!isset($_SESSION['user_id'])) 
    header("Location: login.php"); // Перенаправляем на страницу входа, если пользователь не авторизован

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

// Получение товаров из корзины для текущего пользователя
$userId = $_SESSION['user_id'] ?? 1; // Если пользователь не авторизован, используем ID 1 (для примера)
$stmt = $pdo->prepare("
    SELECT c.id AS cart_id, c.quantity, p.id AS product_id, p.name, p.price, p.images 
    FROM cart c
    JOIN catalog p ON c.product_id = p.id
    WHERE c.user_id = :user_id
");
$stmt->execute(['user_id' => $userId]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Получение привязанных карт пользователя
$stmt = $pdo->prepare("SELECT * FROM cards WHERE user_id = :user_id");
$stmt->execute(['user_id' => $userId]);
$cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Удаление товара из корзины
if (isset($_GET['remove'])) {
    $cartId = $_GET['remove'];
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = :id");
    $stmt->execute(['id' => $cartId]);
    header("Location: korzina.php"); // Перезагружаем страницу
    exit();
}

// Оформление заказа
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $address = $_POST['address'];
    $cardId = $_POST['card_id'];

    // Здесь можно добавить логику оформления заказа, например, создание записи в таблице `orders`
    // После успешного оформления заказа очищаем корзину
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $userId]);

    // Сообщение об успешном оформлении заказа
    $orderSuccess = true;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Техно-Дом | Корзина</title>
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
            padding: 20px;
        }

        .cart-items {
            display: grid;
            gap: 20px;
        }

        .cart-item {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
        }

        .cart-item-details {
            flex: 1;
        }

        .cart-item h3 {
            margin: 0 0 10px;
            font-size: 20px;
        }

        .cart-item p {
            margin: 0;
            font-size: 16px;
            color: #007bff;
        }

        .remove-button {
            padding: 10px 20px;
            background: red;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .remove-button:hover {
            background: darkred;
        }

        .checkout-button {
            padding: 10px 20px;
            background: green;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 20px;
        }

        .checkout-button:hover {
            background: darkgreen;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            width: 100%;
        }

        .modal h2 {
            margin-top: 0;
        }

        .modal label {
            display: block;
            margin-bottom: 10px;
        }

        .modal input, .modal select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .modal button {
            padding: 10px 20px;
            background: green;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .modal button:hover {
            background: darkgreen;
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
            <a href="cart.php">
                <img src="../img/korzina.png" alt="Корзина">
                Корзина
            </a>
        </div>
    </header>

    <!-- Основной контент -->
    <main>
        <div class="container">
            <h1>Корзина</h1>
            <div class="cart-items">
                <?php if (empty($cartItems)): ?>
                    <p>Ваша корзина пуста.</p>
                <?php else: ?>
                    <?php foreach ($cartItems as $item): ?>
                        <div class="cart-item">
                            <img src="data:image/jpeg;base64,<?= base64_encode($item['images']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                            <div class="cart-item-details">
                                <h3><?= htmlspecialchars($item['name']) ?></h3>
                                <p>Цена: <?= number_format($item['price'], 0, '', ' ') ?> ₽</p>
                                <p>Количество: <?= $item['quantity'] ?></p>
                            </div>
                            <a href="korzina.php?remove=<?= $item['cart_id'] ?>" class="remove-button">Удалить</a>
                        </div>
                    <?php endforeach; ?>
                    <button class="checkout-button" onclick="openModal()">Оформить заказ</button>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Модальное окно оформления заказа -->
    <div id="orderModal" class="modal">
        <div class="modal-content">
            <h2>Оформление заказа</h2>
            <form method="POST" action="korzina.php">
                <label for="address">Адрес доставки:</label>
                <input type="text" id="address" name="address" required>

                <label for="card_id">Выберите карту:</label>
                <select id="card_id" name="card_id" required>
                    <?php foreach ($cards as $card): ?>
                        <option value="<?= $card['id'] ?>">
                            <?= htmlspecialchars($card['card_holder']) ?> (**** **** **** <?= substr($card['card_number'], -4) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" name="place_order">Доставить</button>
            </form>
            <button onclick="closeModal()">Закрыть</button>
        </div>
    </div>

    <!-- Сообщение об успешном оформлении заказа -->
    <?php if (isset($orderSuccess) && $orderSuccess): ?>
        <div class="modal" style="display: flex;">
            <div class="modal-content">
                <h2>Ваш заказ оформлен!</h2>
                <p>Ожидайте доставки.</p>
                <button onclick="closeModal()">Закрыть</button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 ТехноДом. Все права защищены.</p>
    </footer>

    <script>
        // Функции для открытия и закрытия модального окна
        function openModal() {
            document.getElementById('orderModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('orderModal').style.display = 'none';
            if (document.querySelector('.modal[style="display: flex;"]')) {
                document.querySelector('.modal[style="display: flex;"]').style.display = 'none';
            }
        }
    </script>
</body>
</html>