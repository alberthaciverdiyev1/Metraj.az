const images = [
    "/webui/images/property-detail-3.jpg",
    "/webui/images/property-detail-4.jpg",
    "/webui/images/property-detail-5.jpg",
    "/webui/images/property-detail-6.jpg"
];
  let currentIndex = 0;
  let slideshow;

  function openModal(index) {
    currentIndex = index;
    document.getElementById("modal").style.display = "flex";
    updateModal();
  }

  function closeModal() {
    document.getElementById("modal").style.display = "none";
    clearInterval(slideshow);
  }

  function updateModal() {
    document.getElementById("modal-image").src = images[currentIndex];
    document.getElementById("counter").innerText = `${currentIndex + 1}/${images.length}`;
  }

  function nextImage() {
    currentIndex = (currentIndex + 1) % images.length;
    updateModal();
  }

  function prevImage() {
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    updateModal();
  }

  function startSlideshow() {
    clearInterval(slideshow);
    slideshow = setInterval(nextImage, 3000);
  }

  function toggleFullscreen() {
    const modal = document.getElementById("modal");
    if (!document.fullscreenElement) {
      modal.requestFullscreen();
    } else {
      document.exitFullscreen();
    }
  }

  function toggleThumbnails() {
    const thumbs = document.getElementById("thumbnails");
    thumbs.style.display = thumbs.style.display === 'flex' ? 'none' : 'flex';
  }

  function shareImage() {
    const url = images[currentIndex];
    navigator.clipboard.writeText(url).then(() => {
      alert("Image URL copied to clipboard");
    });
  }

//read-more
document.addEventListener('DOMContentLoaded', function() {
    const readMore = document.querySelector('.read-more');
    const fullText = document.querySelector('.units');
    

    if (fullText.scrollHeight > fullText.clientHeight) {
        readMore.style.display = 'inline-flex';
        
        readMore.addEventListener('click', function() {
            fullText.classList.toggle('expanded');
            
            if (fullText.classList.contains('expanded')) {
                readMore.innerHTML = 'Read less <i class="bi bi-arrow-up-circle"></i>';
            } else {
                readMore.innerHTML = 'Read more <i class="bi bi-arrow-down-circle"></i>';
            }
        });
    } else {
        readMore.style.display = 'none'; 
    }
});

//accordion
  const accordionItems = document.querySelectorAll('.accordion-item');

    accordionItems.forEach(item => {
      const header = item.querySelector('.accordion-header');
      header.addEventListener('click', () => {
        item.classList.toggle('active');
      });
    });

//calculator
 function calculateLoan() {
      const amount = parseFloat(document.getElementById('amount').value);
      const down = parseFloat(document.getElementById('down').value);
      const months = parseFloat(document.getElementById('months').value);
      const rate = parseFloat(document.getElementById('rate').value) / 100 / 12;
      const tax = parseFloat(document.getElementById('tax').value.replace('$','')) / 12;
      const insurance = parseFloat(document.getElementById('insurance').value.replace('$','')) / 12;

      if (!months || isNaN(amount) || isNaN(down)) {
        alert("Please fill in all fields.");
        return;
      }

      const loanAmount = amount - down;
      let monthlyPayment = 0;

      if (rate === 0) {
        monthlyPayment = loanAmount / months;
      } else {
        monthlyPayment = loanAmount * rate * Math.pow(1 + rate, months) / (Math.pow(1 + rate, months) - 1);
      }

      const total = monthlyPayment + tax + insurance;
      document.getElementById('resultText').innerHTML = `Your estimated monthly payment: <strong style="color:orange;">$${total.toFixed(2)}</strong>`;
      document.getElementById('resultModal').style.display = 'flex';
    }

    function closeModal() {
      document.getElementById('resultModal').style.display = 'none';
    }


document.getElementById("contact-form").addEventListener("submit", function(event) {
      event.preventDefault();
      const name = document.getElementById("name").value;
      const message = document.getElementById("message").value;
      const subject = encodeURIComponent("Contact from " + name);
      const body = encodeURIComponent(message);
      window.location.href = `mailto:arzuuimammadova@gmail.com?subject=${subject}&body=${body}`;
    });


    //scroll
    window.addEventListener('scroll', function () {
  const contactCard = document.querySelector('.contact-card');
  const stopElement = document.querySelector('x-connect-agent'); 

  const cardRect = contactCard.getBoundingClientRect();
  const stopRect = stopElement.getBoundingClientRect();

  if (cardRect.bottom > stopRect.top) {
    contactCard.style.position = 'absolute';
    contactCard.style.top = (stopRect.top - cardRect.height) + window.scrollY + 'px';
  } else {
    contactCard.style.position = 'sticky';
    contactCard.style.top = '30px';
  }
});
