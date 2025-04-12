function showFeedbackForm() {
    document.getElementById('feedbackForm').style.display = 'block';
  }

  function submitFeedback() {
    var name = document.getElementById('name').value;
    var feedback = document.getElementById('feedback').value;

    if (name && feedback) {
      var review = { name: name, feedback: feedback };
      saveReview(review);
      displayReview(review);

      // Очистка формы
      document.getElementById('name').value = '';
      document.getElementById('feedback').value = '';

      // Скрытие формы
      document.getElementById('feedbackForm').style.display = 'none';
    } else {
      alert('Пожалуйста, заполните все поля');
    }
  }

  function saveReview(review) {
    var reviews = JSON.parse(localStorage.getItem('reviews')) || [];
    reviews.push(review);
    localStorage.setItem('reviews', JSON.stringify(reviews));
  }

  function displayReview(review) {
    var reviewSection = document.createElement('div');
    reviewSection.classList.add('review');

    var reviewTitle = document.createElement('h3');
    reviewTitle.innerText = review.name;

    var reviewContent = document.createElement('p');
    reviewContent.innerText = review.feedback;

    reviewSection.appendChild(reviewTitle);
    reviewSection.appendChild(reviewContent);

    document.getElementById('reviews').appendChild(reviewSection);
  }

  function loadReviews() {
    var reviews = JSON.parse(localStorage.getItem('reviews')) || [];
    reviews.forEach(displayReview);
  }

  window.onload = loadReviews;