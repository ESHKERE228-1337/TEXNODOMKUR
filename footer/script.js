document.querySelectorAll('.apply-button').forEach(function(button) {
    button.addEventListener('click', function() {
      document.querySelector('.apply-form').classList.remove('hidden');
    });
  });
  document.getElementById('applicationForm').addEventListener('submit', function(event) {
    event.preventDefault();
    // Здесь можно добавить код для обработки формы
    alert('Ваша заявка отправлена!');
  });



  











  // JavaScript для плавной прокрутки
document.querySelectorAll('.apply-button').forEach(function(button) {
    button.addEventListener('click', function() {
      var target = document.querySelector(this.getAttribute('data-target'));
      target.scrollIntoView({ behavior: 'smooth' });
      target.classList.remove('hidden');
    });
  });
  