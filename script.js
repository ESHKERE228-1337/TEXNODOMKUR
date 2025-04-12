document.addEventListener('DOMContentLoaded', () => {
    let currentSlide = 0;
    const slides = document.querySelector('.slides');
    const totalSlides = document.querySelectorAll('.slide').length;
    const slideWidth = document.querySelector('.slide').clientWidth;

    function showSlide(index) {
        if (index >= totalSlides) {
            currentSlide = 0;
        } else if (index < 0) {
            currentSlide = totalSlides - 1;
        } else {
            currentSlide = index;
        }

        const offset = -currentSlide * slideWidth;
        slides.style.transform = `translateX(${offset}px)`;
    }

    document.querySelector('.prev').addEventListener('click', () => {
        clearInterval(autoSlide);
        showSlide(currentSlide - 1);
        restartAutoSlide();
    });

    document.querySelector('.next').addEventListener('click', () => {
        clearInterval(autoSlide);
        showSlide(currentSlide + 1);
        restartAutoSlide();
    });

    let autoSlide = setInterval(() => {
        showSlide(currentSlide + 1);
    }, 5000);

    function restartAutoSlide() {
        autoSlide = setInterval(() => {
            showSlide(currentSlide + 1);
        }, 5000);
    }
});
