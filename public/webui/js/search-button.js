    const tagContainer = document.querySelector('#selectedTags');
    const modal = document.querySelector('#filterModal');
    const openBtn = document.querySelector('#openSearchBtn');
    const closeBtn = document.querySelector('#closeModalBtn');
    const filterForm = document.querySelector('#filterForm');
    const resetBtn = document.querySelector('#resetBtn');
    const toggleBtn = document.querySelector('#toggleBtn');
    const filterPanel = document.querySelector('.filter-panel');

    const dropdownData = {
      districts: ['Binəqədi', 'Nərimanov', 'Nəsimi', 'Xətai', 'Yasamal'],
      metros: ['28 May', 'Gənclik', 'Elmlər', 'İnşaatçılar'],
      landmarks: ['Gənclik Mall', 'Targovu', 'Flame Towers', 'Təhsil Nazirliyi']
    };

    function initToggleButtons() {
      document.querySelectorAll('.toggle-group .toggle').forEach(btn => {
        btn.addEventListener('click', (e) => {
          const group = e.target.closest('.toggle-group');
          group.querySelectorAll('.toggle').forEach(b => b.classList.remove('active'));
          e.target.classList.add('active');
        });
      });
    }

    function initLocationPills() {
      document.querySelectorAll('.pill').forEach(pill => {
        pill.addEventListener('click', () => openDropdown(pill.dataset.target));
      });
    }

    function openDropdown(id) {
      const dropdown = document.querySelector(`#${id}`);

      dropdown.innerHTML = dropdownData[id]
        .map(item => `<button class="dd-item">${item}</button>`)
        .join('');

      dropdown.classList.remove('hidden');

      const outsideClickHandler = (e) => {
        if (!dropdown.contains(e.target)) {
          dropdown.classList.add('hidden');
          window.removeEventListener('click', outsideClickHandler);
        }
      };

      setTimeout(() => window.addEventListener('click', outsideClickHandler));

      document.querySelectorAll('.dd-item', dropdown).forEach(btn => {
        btn.addEventListener('click', (e) => {
          addTag(e.target.textContent, id);
          dropdown.classList.add('hidden');
          window.removeEventListener('click', outsideClickHandler);
        });
      });
    }

    function addTag(label, scope) {
      if (document.querySelectorAll(`.tag[data-scope="${scope}"][data-value="${label}"]`).length) return;

      const tag = document.createElement('span');
      tag.className = 'tag';
      tag.dataset.scope = scope;
      tag.dataset.value = label;
      tag.innerHTML = `${label} <button aria-label="Sil">&times;</button>`;

      tag.querySelector('button').onclick = () => tag.remove();

      tagContainer.appendChild(tag);
    }

    function resetForm() {
      filterForm.reset();

      document.querySelectorAll('.toggle-group').forEach(group => {
        group.querySelectorAll('.toggle').forEach(btn => btn.classList.remove('active'));
        group.querySelector('.toggle').classList.add('active');
      });

      tagContainer.innerHTML = '';
    }
    function togglePanelCollapse() {
      filterPanel.classList.toggle('collapsed');

      toggleBtn.innerHTML = filterPanel.classList.contains('collapsed')
        ? 'Genişləndir <i class="bi bi-chevron-down"></i>'
        : 'Gizlət <i class="bi bi-chevron-up"></i>';
    }

    function handleFormSubmit(e) {
      e.preventDefault();

      const formData = new FormData(e.target);
      const query = Object.fromEntries(formData.entries());

      const activeRepairButton = document.querySelector('.toggle-group[data-name="repair"] .toggle.active');
      if (activeRepairButton) {
          query.repair = activeRepairButton.dataset.value;
      }

      query.selected = [...document.querySelectorAll('.tag')].map(tag => ({
        scope: tag.dataset.scope,
        value: tag.dataset.value
      }));

      console.log('FILTER PAYLOAD:', query);
      alert('Konsolda "FILTER PAYLOAD"-a baxın 😉');
      closeModal();
    }

    function openModal() {
      modal.style.display = 'block';
      document.body.style.overflow = 'hidden';
    }

    function closeModal() {
      modal.style.display = 'none';
      document.body.style.overflow = 'auto';
    }

    function initEventListeners() {
      filterForm.addEventListener('submit', handleFormSubmit);

      resetBtn.addEventListener('click', resetForm);

      toggleBtn.addEventListener('click', togglePanelCollapse);

      openBtn.addEventListener('click', openModal);
      closeBtn.addEventListener('click', closeModal);

      window.addEventListener('click', (e) => {
        if (e.target === modal) {
          closeModal();
        }
      });
    }

    document.addEventListener('DOMContentLoaded', () => {
      initToggleButtons();
      initLocationPills();
      initEventListeners();
    });