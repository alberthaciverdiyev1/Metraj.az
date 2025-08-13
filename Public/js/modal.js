document.addEventListener('DOMContentLoaded', function() {
    
    const btnAdvance = document.getElementById('btn-advance');
    const btnPremium = document.getElementById('btn-premium');
    const modalAdvance = document.getElementById('modal-advance');
    const modalPremium = document.getElementById('modal-premium');
    
function openModal(modal) {
    if (modal) {
        modal.style.display = 'flex'; 
        setTimeout(() => {
            modal.classList.remove('invisible');
        }, 10);
    }
}

function closeModal(modal) {
    if (modal) {
        modal.classList.add('invisible');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 200); 
    }
}

    
    if (btnAdvance) {
        btnAdvance.addEventListener('click', function() {
            openModal(modalAdvance);
        });
    }
    
    if (btnPremium) {
        btnPremium.addEventListener('click', function() {
            openModal(modalPremium);
        });
    }
    
    const closeButtons = document.querySelectorAll('[data-close]');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modalId = this.getAttribute('data-close');
            const modal = document.getElementById(modalId);
            closeModal(modal);
        });
    });
    
    window.addEventListener('click', function(event) {
        if (event.target === modalAdvance) {
            closeModal(modalAdvance);
        }
        if (event.target === modalPremium) {
            closeModal(modalPremium);
        }
    });
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal(modalAdvance);
            closeModal(modalPremium);
        }
    });
    
    const radioButtons = document.querySelectorAll('input[type="radio"]');
    radioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            const labels = this.closest('.flex').parentElement.querySelectorAll('label');
            labels.forEach(label => label.classList.remove('bg-blue-50', 'border-blue-300'));
            
            if (this.checked) {
                this.closest('label').classList.add('bg-blue-50', 'border-blue-300');
            }
        });
    });
});

const style = document.createElement('style');
style.textContent = `
    #modal-advance,
    #modal-premium {
        display: none;
        background-color: rgba(0, 0, 0, 0.5) !important;
    }
    
    /* Radio button seçimi zamanı stil */
    .flex label.bg-blue-50 {
        background-color: #eff6ff;
        border-color: #93c5fd;
    }
`;
document.head.appendChild(style);