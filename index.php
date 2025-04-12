
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Техно-Дом</title>
</head>
<body>

<header>
    <div class="logo-container">
        <img src="img/logo.png" alt="Логотип Техно-Дом" class="logo">
        <span class="site-name">Техно-Дом</span>
    </div>
    <div class="nav-buttons">
        <a href="components/vhod.php">
            <img src="img/vhod.png" alt="Личный кабинет">
            Личный кабинет
        </a>
        <a href="tovar/favorites.php">
            <img src="img/love.png" alt="Избранное">
            Избранное
        </a>
        <a href="tovar/korzina.php">
            <img src="img/korzina.png" alt="Корзина">
            Корзина
        </a>
    </div>
</header>

<!-- Слайдер -->
<div class="slider">
    <div class="slides">
        <div class="slide"><img src="img/slide3.png" alt="Слайд 1"></div>
        <div class="slide"><img src="img/slide1.png" alt="Слайд 2"></div>
        <div class="slide"><img src="img/slide2.jpg" alt="Слайд 3"></div>
        <div class="slide"><img src="img/slide4.png" alt="Слайд 4"></div>
    </div>
    <div class="slider-controls">
        <button class="slider-button prev">&#10094;</button>
        <button class="slider-button next">&#10095;</button>
    </div>
</div>
<!-- Полоска -->
<div class="separator"></div>

<!-- Блок преимуществ -->
<div class="advantages">
    <div class="advantage">
        <img src="img/123.png" alt="Опыт">
        <p><span>90 лет</span><br>Работаем с 1930 года</p>
    </div>
    <div class="advantage">
        <img src="img/222.png" alt="Магазины">
        <p><span>343 магазина</span><br>в 265 городах РФ</p>
    </div>
    <div class="advantage">
        <img src="img/3333.png" alt="Круглосуточно">
        <p><span>24/7</span><br>Работаем для вас круглосуточно</p>
    </div>
    <div class="advantage">
        <img src="img/4.png" alt="Доставка">
        <p>Оперативная доставка</p>
    </div>
    <div class="advantage">
        <img src="img/5.png" alt="Клиенты">
        <p><span>10 млн клиентов</span><br>посетили наши магазины за последний год</p>
    </div>
    <div class="advantage">
        <img src="img/6.png" alt="Кредит">
        <p>Покупайте в кредит<br>на сайте и в наших магазинах</p>
    </div>
</div>
<div class="separator"></div>
<!-- Блок с товарами-->
<div class="button-container">
    <div class="button-block" onclick="window.location.href='tovar/tovar1.php';">
        <img src="img/vst.jpg" alt="Встраиваемая техника">
        <span>Встраиваемая техника</span>
    </div>
    <div class="button-block" onclick="window.location.href='tovar/tovar2.php';">
        <img src="img/bit.jpg" alt="Крупная бытовая техника">
        <span>Крупная бытовая техника</span>
    </div>
    <div class="button-block" onclick="window.location.href='tovar/tovar3.php';">
        <img src="img/pil.jpg" alt="Техника для уборки">
        <span>Техника для уборки</span>
    </div>
    <div class="button-block" onclick="window.location.href='tovar/tovar4.php';">
        <img src="img/komp.jpg" alt="комп">
        <span>Компьютеры</span>
    </div>
    <div class="button-block" onclick="window.location.href='tovar/tovar5.php';">
        <img src="img/nout.jpg" alt="Швейное оборудование">
        <span>Ноутбуки</span>
    </div>
    <div class="button-block" onclick="window.location.href='tovar/tovar6.php';">
        <img src="img/TEL.jpg" alt="Фильтры для воды">
        <span>Смартфоны</span>
    </div>
    <div class="button-block" onclick="window.location.href='climate.html';">
        <img src="img/obog.jpg" alt="Климатическое оборудование">
        <span>Климатическое оборудование</span>
    </div>
    <div class="button-block" onclick="window.location.href='kitchen.html';">
        <img src="img/women.jpg" alt="Техника для кухни">
        <span>Техника для кухни</span>
    </div>
    <div class="button-block" onclick="window.location.href='clothing-care.html';">
        <img src="img/pris.jpg" alt="Уход за одеждой">
        <span>Игровые приставки</span>
    </div>
</div>



<div class="news-container">
    <div class="news-title">Новости о технике</div>
    <div class="news-grid">
        <div class="news-item">
            <img src="img/novost1.jpg" alt="Новость 1">
            <div class="news-content">
                <h3>Новые холодильники с AI</h3>
                <p>Компания XYZ представила новые холодильники с искусственным интеллектом, которые могут самостоятельно заказывать продукты.</p>
                <a href="news1.html">Читать далее</a>
            </div>
        </div>
        <div class="news-item">
            <img src="img/news2.jpg" alt="Новость 2">
            <div class="news-content">
                <h3>Роботы-пылесосы нового поколения</h3>
                <p>На рынок вышли роботы-пылесосы с улучшенной навигацией и возможностью мытья полов.</p>
                <a href="news2.html">Читать далее</a>
            </div>
        </div>
        <div class="news-item">
            <img src="img/news3.jpg" alt="Новость 3">
            <div class="news-content">
                <h3>Умные часы с EKG</h3>
                <p>Новые умные часы от ABC теперь поддерживают функцию EKG для мониторинга здоровья сердца.</p>
                <a href="news3.html">Читать далее</a>
            </div>
        </div>
    </div>
</div>





<div class="image-gallery">
    <div class="image-gallery-title"></div>
    <div class="image-grid">
        <div class="image-item">
            <img src="img/ыыы1.jpg" alt="Для любителей музыки">
            <p>Для любителей музыки</p>
        </div>
        <div class="image-item">
            <img src="img/ыыы2.jpg" alt="За покупками!">
            <p>За покупками!</p>
        </div>
        <div class="image-item">
            <img src="img/ыыы3.jpg" alt="Распродажа консольных игр">
            <p>Распродажа консольных игр</p>
        </div>
    </div>
</div>











<script src="script.js" defer></script>
<footer class="footer">
    <div class="footer-links">
        <a href="footer/about.html">О компании</a>
        <a href="footer/stores.html">Магазины</a>
        <a href="footer/vak.html">Вакансии</a>
        <a href="footer/otziv.html">Отзывы</a>
    </div>
    <div class="social-icons">
        <a href="https://vk.com/ваша-страница" target="_blank"><img src="img/icons8-vk-48.png" alt="VK"></a>
        <a href="https://tg.com/ваша-страница" target="_blank"><img src="img/icons8-телеграмма-app-48.png" alt="Telegram"></a>
        <a href="https://www.instagram.com/ваша-страница" target="_blank"><img src="img/icons8-instagram-48.png" alt="Instagram"></a>
    </div>
    <p>&copy; 2024 ТехноДом. Все права защищены.</p>
</footer>
</body>
</html>
