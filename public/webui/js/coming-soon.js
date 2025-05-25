 const form = document.getElementById('emailForm');
  const emailInput = document.getElementById('emailInput');
  const errorMsg = document.getElementById('errorMsg');

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const emailValue = emailInput.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (emailValue === '' || !emailRegex.test(emailValue)) {
      errorMsg.style.display = 'block';
      emailInput.classList.add('error-border');
    } else {
      errorMsg.style.display = 'none';
      emailInput.classList.remove('error-border');
    
      // form.submit();
      alert("Email is valid!");
    }
  });