document.addEventListener('DOMContentLoaded', () => {
  const advanceModal = document.getElementById('modal-advance');
  const premiumModal = document.getElementById('modal-premium');
  const advanceBtn = document.getElementById('btn-advance');
  const premiumBtn = document.getElementById('btn-premium');

  // Modal başlanğıcda gizli olmalıdır
  [advanceModal, premiumModal].forEach(modal => {
    if (modal) {
      modal.classList.add('hidden');
      // Əlavə olaraq display none da təyin edək
      modal.style.display = 'none';
    }
  });

  function lockBodyScroll() {
    const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
    
    if (scrollbarWidth > 0) {
      document.body.style.paddingRight = `${scrollbarWidth}px`;
      
      document.querySelectorAll('.fixed, .sticky').forEach(el => {
        el.style.paddingRight = `${scrollbarWidth}px`;
      });
    }
    
    document.body.style.overflow = 'hidden';
  }

  function unlockBodyScroll() {
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
    
    document.querySelectorAll('.fixed, .sticky').forEach(el => {
      el.style.paddingRight = '';
    });
  }

  function showModal(modal) {
    if (!modal) return;
    
    // Modalı göstər
    modal.style.display = 'flex';
    modal.classList.remove('hidden');
    
    // Kiçik timeout ilə transition üçün
    setTimeout(() => {
      modal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
    }, 10);
    
    lockBodyScroll();
  }

  function hideModal(modal) {
    if (!modal) return;
    
    // Modalı gizlət
    modal.style.backgroundColor = 'transparent';
    modal.classList.add('hidden');
    modal.style.display = 'none';
    
    // Başqa modal açıqdırsa scroll-u açma
    const anyModalOpen = [advanceModal, premiumModal].some(
      m => m && !m.classList.contains('hidden')
    );
    
    if (!anyModalOpen) {
      unlockBodyScroll();
    }
  }

  // Button eventləri
  if (advanceBtn && advanceModal) {
    advanceBtn.addEventListener('click', e => {
      e.preventDefault();
      showModal(advanceModal);
    });
  }

  if (premiumBtn && premiumModal) {
    premiumBtn.addEventListener('click', e => {
      e.preventDefault();
      showModal(premiumModal);
    });
  }

  document.addEventListener('click', e => {
    if (e.target.hasAttribute('data-close')) {
      e.preventDefault();
      e.stopPropagation();
      const modalId = e.target.getAttribute('data-close');
      const modal = document.getElementById(modalId);
      if (modal) hideModal(modal);
      return;
    }

    // Overlay click - modal background-una click edildikdə
    if (e.target === advanceModal) {
      hideModal(advanceModal);
    }
    if (e.target === premiumModal) {
      hideModal(premiumModal);
    }
  });

  // Modal içərisindəki content-ə click edildikdə modalın bağlanmaması üçün
  [advanceModal, premiumModal].forEach(modal => {
    if (modal) {
      const modalContent = modal.querySelector('.bg-white');
      if (modalContent) {
        modalContent.addEventListener('click', e => {
          e.stopPropagation();
        });
      }
    }
  });

  // ESC ilə bağlama
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      if (advanceModal && !advanceModal.classList.contains('hidden')) {
        hideModal(advanceModal);
      }
      if (premiumModal && !premiumModal.classList.contains('hidden')) {
        hideModal(premiumModal);
      }
    }
  });
});