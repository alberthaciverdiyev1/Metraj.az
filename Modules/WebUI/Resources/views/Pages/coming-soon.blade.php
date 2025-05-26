    @include('webui::partials.head')
    <section id="bg">
        <header>
            <div class=""><i class="bi bi-record-circle"></i> PMX</div>
            <a class="link">Say Hello</a>
        </header>





        <main>

            <h3>Something great is on the way</h3>

            <h1>Coming Soon </h1>
            <div class="time">
                <div class="time-part">
                    <span class="number" id="days">00</span>
                    <p>days</p>
                </div>
                <span class="colon">:</span>
                <div class="time-part">
                    <span class="number" id="hours">00</span>
                    <p>hours</p>
                </div>
                <span class="colon">:</span>
                <div class="time-part">
                    <span class="number" id="minutes">00</span>
                    <p>min</p>
                </div>
                <span class="colon">:</span>
                <div class="time-part">
                    <span class="number" id="seconds">00</span>
                    <p>seconds</p>
                </div>
            </div>


            <div class="scroll-down">
                <a href="#ready">Scroll down</a>
                <i class="bi bi-arrow-down-short"></i>
            </div>


        </main>


    </section>


    <section id="ready">
        <h3>Are you ready?</h3>
        <p>I'm a paragraph. Click here to add your own text and edit me. It’s easy. Just click “Edit Text” or double click me to add your own content and make changes to the font.

        <figure>
            <img src="webui/images/sendimage.png" alt="Logo">
        </figure>

        <p class="desc">Be the first to hear when we go live</p>

        <form id="emailForm">        
                 <label for="email">Email*</label>
            <div class="email-input">
                
                <input type="text" name="email" id="emailInput">
                <button type="submit" class="send-btn">Notify Me</button>
            </div>

                         <span class="error" id="errorMsg">Enter an email address like example@mysite.com.</span>
            
        </form>

        </p>
    </section>


    <footer class="mx-auto container px-3 ">
        <div class="sosials">
            <a href="">Facebokk</a>
            <a href="">Linkendl</a>
            <a href="">X</a>
        </div>

        <div class="site">
            <a href="">info@mysite.com</a>
        </div>

        <div class="copyright">
            © 2035 by PMX. Powered and secured by <a href="">Wix</a>
        </div>
    </footer>


    <script>
         const now = new Date().getTime();
  const countdownEnd = now + 24 * 60 * 60 * 1000;

  const daysEl = document.getElementById('days');
  const hoursEl = document.getElementById('hours');
  const minutesEl = document.getElementById('minutes');
  const secondsEl = document.getElementById('seconds');

  const timer = setInterval(() => {
    const currentTime = new Date().getTime();
    const distance = countdownEnd - currentTime;

    if (distance <= 0) {
      clearInterval(timer);
      daysEl.textContent = "00";
      hoursEl.textContent = "00";
      minutesEl.textContent = "00";
      secondsEl.textContent = "00";
      alert("24 saat tamam oldu!");
      return;
    }

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    daysEl.textContent = String(days).padStart(2, "0");
    hoursEl.textContent = String(hours).padStart(2, "0");
    minutesEl.textContent = String(minutes).padStart(2, "0");
    secondsEl.textContent = String(seconds).padStart(2, "0");
  }, 1000);

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
    </script>