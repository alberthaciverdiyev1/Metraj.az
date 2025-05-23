  const headers = document.querySelectorAll('.accordion-header');
  headers.forEach(header => {
    header.addEventListener('click', () => {
      const isActive = header.classList.contains('active');
      headers.forEach(h => {
        h.classList.remove('active');
        h.querySelector('.arrow').textContent = '▶';
        h.nextElementSibling.style.display = 'none';
      });
      if (!isActive) {
        header.classList.add('active');
        header.querySelector('.arrow').textContent = '▼';
        header.nextElementSibling.style.display = 'block';
      }
    });
  });