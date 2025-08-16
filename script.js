// Kontaktformular AJAX
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById('contactForm');
  if (!form) return;

  form.addEventListener('submit', function(e){
    e.preventDefault();

    const formData = new FormData(this);

    fetch('mail.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.text())
    .then(data => {
      document.getElementById('response').innerText = data;
      grecaptcha.reset();
      form.reset();
    })
    .catch(error => {
      document.getElementById('response').innerText = 'Fehler beim Senden!';
      console.error(error);
    });
  });
});
