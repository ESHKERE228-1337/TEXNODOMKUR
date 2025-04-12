// Открытие модального окна при клике на картинку товара
const productImages = document.querySelectorAll('.product-image');
const modal = document.getElementById('modal');
const modalImage = document.querySelector('.modal-image');
const modalTitle = document.querySelector('.modal-title');
const modalPrice = document.querySelector('.modal-price');
const modalDescription = document.querySelector('.modal-description');
const closeModal = document.querySelector('.close');

productImages.forEach((image) => {
    image.addEventListener('click', () => {
        const productCard = image.closest('.product-card');
        modalImage.src = image.src;
        modalTitle.textContent = productCard.querySelector('h3').textContent;
        modalPrice.textContent = productCard.querySelector('.price').textContent;
        modalDescription.textContent = "Описание товара: Lorem ipsum dolor sit amet, consectetur adipiscing elit.";
        modal.style.display = 'flex';
    });
});

// Закрытие модального окна
closeModal.addEventListener('click', () => {
    modal.style.display = 'none';
});

window.addEventListener('click', (event) => {
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});

// Обновление значения цены в фильтре
const priceSlider = document.querySelector('.price-slider');
const priceValue = document.getElementById('price-value');

priceSlider.addEventListener('input', () => {
    priceValue.textContent = priceSlider.value;
});