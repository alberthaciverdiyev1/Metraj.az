

    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll(".toggle-btn");
        const inactiveButtonBaseClasses = ["bg-white", "text-black", "border", "border-gray-300"];
        const inactiveButtonHoverClasses = ["hover:bg-gray-100", "hover:text-black"]; 
        const activeButtonClasses = ["bg-red-400", "text-white"];

        toggleButtons.forEach(btn => {
          
            if (!btn.classList.contains("bg-red-400")) {
                btn.classList.add(...inactiveButtonHoverClasses);
            }

            btn.addEventListener("click", () => {
                toggleButtons.forEach(b => {
                    b.classList.remove(...activeButtonClasses);
                    b.classList.add(...inactiveButtonBaseClasses);
                    b.classList.add(...inactiveButtonHoverClasses);
                    b.parentElement.querySelector(".triangle-indicator").classList.add("hidden");
                });

                btn.classList.remove(...inactiveButtonBaseClasses);
                btn.classList.remove(...inactiveButtonHoverClasses); 
                btn.classList.add(...activeButtonClasses);
                btn.parentElement.querySelector(".triangle-indicator").classList.remove("hidden");
            });
        });

        const filterBtn = document.getElementById('filterButton');
        const modal = document.getElementById('filterModal');

        filterBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (modal.classList.contains('invisible')) {
                modal.classList.remove('invisible', 'opacity-0', '-translate-y-3');
                modal.classList.add('visible', 'opacity-100', 'translate-y-0');
            } else {
                modal.classList.remove('visible', 'opacity-100', 'translate-y-0');
                modal.classList.add('invisible', 'opacity-0', '-translate-y-3');
            }
        });

        document.addEventListener('click', (e) => {
            if (modal.classList.contains('visible') && !modal.contains(e.target) && !filterBtn.contains(e.target)) {
                modal.classList.remove('visible', 'opacity-100', 'translate-y-0');
                modal.classList.add('invisible', 'opacity-0', '-translate-y-3');
            }
        });

        const selectWrappers = document.querySelectorAll('.select-wrapper');
        selectWrappers.forEach(wrapper => {
            const select = wrapper.querySelector('.custom-select');
            select.addEventListener('focus', () => {
                wrapper.classList.add('focused');
            });
            select.addEventListener('blur', () => {
                wrapper.classList.remove('focused');
            });
        });
    });

    document.getElementById('downPayment').addEventListener('input', function() {
      const total = parseFloat(document.getElementById('totalAmount').value) || 0;
      const downPayment = parseFloat(this.value) || 0;
      const percent = (downPayment / total * 100).toFixed(2);
      document.getElementById('downPaymentPercent').value = percent;
    });

    document.getElementById('downPaymentPercent').addEventListener('input', function() {
      const total = parseFloat(document.getElementById('totalAmount').value) || 0;
      const percent = parseFloat(this.value) || 0;
      const downPayment = (total * percent / 100).toFixed(2);
      document.getElementById('downPayment').value = downPayment;
    });

    function calculatePayment() {
      const total = parseFloat(document.getElementById('totalAmount').value) || 0;
      const down = parseFloat(document.getElementById('downPayment').value) || 0;
      const interest = parseFloat(document.getElementById('interestRate').value) / 100 / 12;
      const years = parseInt(document.getElementById('amortizationPeriod').value);
      const months = years * 12;
      const tax = (parseFloat(document.getElementById('propertyTax').value) || 0) / 12;
      const insurance = (parseFloat(document.getElementById('homeInsurance').value) || 0) / 12;

      let principal = total - down;
      let monthly = 0;

      if (interest > 0 && months > 0) {
        monthly = (principal * interest * Math.pow(1 + interest, months)) / (Math.pow(1 + interest, months) - 1);
      } else if (months > 0) {
        monthly = principal / months;
      }

      const payment = Math.round(monthly + tax + insurance);

      document.getElementById('paymentDisplay').textContent = `$${payment.toLocaleString()}`;
      document.getElementById('modalPayment').textContent = `$${payment.toLocaleString()}`;
      document.getElementById('modal').classList.remove('hidden');
      document.getElementById('modal').classList.add('flex');
    }

    function closeModal() {
      document.getElementById('modal').classList.add('hidden');
      document.getElementById('modal').classList.remove('flex');
    }

    function resetForm() {
      document.getElementById('totalAmount').value = '100000';
      document.getElementById('downPayment').value = '20000';
      document.getElementById('downPaymentPercent').value = '20';
      document.getElementById('interestRate').value = '5';
      document.getElementById('amortizationPeriod').value = '0';
      document.getElementById('propertyTax').value = '3000';
      document.getElementById('homeInsurance').value = '1200';
      document.getElementById('paymentDisplay').textContent = '$0';
    }


    //AOS DISCOVER
    AOS.init({
        duration: 800,
        once: true
    });

 
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.tab-btn');
        const contents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
               
                tabs.forEach(btn => btn.classList.remove('active'));
                contents.forEach(content => content.classList.remove('active'));

              
                tab.classList.add('active');
                const tabId = tab.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');

                AOS.refresh();
            });
        });
    });


    document.addEventListener("DOMContentLoaded", function () {
   function animateText(element) {
    const text = element.textContent;
    element.textContent = '';

    for (let i = 0; i < text.length; i++) {
        const span = document.createElement('span');

        if (text[i] === ' ') {
            span.innerHTML = '&nbsp;';
        } else {
            span.textContent = text[i];
        }

        span.style.animationDelay = (i * 0.1) + 's';
        element.appendChild(span);
    }
}


    function handleScroll() {
        const sections = document.querySelectorAll('.title-section');
        const windowHeight = window.innerHeight;

        sections.forEach(section => {
            const rect = section.getBoundingClientRect();

            if (!section.classList.contains('animated') && rect.top < windowHeight - 200) {
                const title = section.querySelector('.animate-title');
                const subtitle = section.querySelector('.animate-subtitle');

                if (title) animateText(title);
                if (subtitle) subtitle.classList.add('visible');

                section.classList.add('animated'); 
            }
        });
    }

    window.addEventListener('scroll', handleScroll);
    handleScroll();
});
