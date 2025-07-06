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
        .map(item => `<li class="dd-item">${item}</li>`)
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
    alert('filter payloada yazildi');
    closeModal();
}

function openModal() {
    modal.classList.add('visible');
    // document.body.style.overflow = 'hidden';
}

function closeModal() {
    modal.classList.remove('visible');
    document.body.style.overflow = 'auto';
}

function initEventListeners() {
    filterForm.addEventListener('submit', handleFormSubmit);
    resetBtn.addEventListener('click', resetForm);
    toggleBtn.addEventListener('click', togglePanelCollapse);

    openBtn.addEventListener('click', () => {
        if (modal.classList.contains('visible')) {
            closeModal();
        } else {
            openModal();
        }
    });

    closeBtn.addEventListener('click', closeModal);

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    modal.classList.remove('visible');
    document.body.style.overflow = 'auto';

    initToggleButtons();
    initLocationPills();
    initEventListeners();
});


document.addEventListener("DOMContentLoaded", function () {
  function initCustomSelect(config) {
    const {
      containerId,
      buttonId,
      optionsId,
      textId,
      inputId,
      iconId
    } = config;
    

    const container = document.getElementById(containerId);
    const button = document.getElementById(buttonId);
    const optionsList = document.getElementById(optionsId);
    const textSpan = document.getElementById(textId);
    const hiddenInput = document.getElementById(inputId);
    const icon = document.getElementById(iconId);

    if (!container || !button || !optionsList || !textSpan || !hiddenInput || !icon) return;

    button.addEventListener("click", () => {
      optionsList.classList.toggle("hidden");
      icon.classList.toggle("rotate-180");
    });

    optionsList.querySelectorAll("li").forEach((option) => {
      option.addEventListener("click", () => {
        const value = option.getAttribute("data-value");
        const text = option.textContent;

        textSpan.textContent = text;
        hiddenInput.value = value;

        optionsList.classList.add("hidden");
        icon.classList.remove("rotate-180");
      });
    });

    document.addEventListener("click", (e) => {
      if (!container.contains(e.target)) {
        optionsList.classList.add("hidden");
        icon.classList.remove("rotate-180");
      }
    });
  }

  initCustomSelect({
    containerId: "customSelectContainer",
    buttonId: "customSelectButton",
    optionsId: "customSelectOptions",
    textId: "customSelectText",
    inputId: "selectedCityInput",
    iconId: "dropdownIcon",
  });

  initCustomSelect({
    containerId: "customConditionContainer",
    buttonId: "customConditionButton",
    optionsId: "customConditionOptions",
    textId: "customConditionText",
    inputId: "selectedConditionInput",
    iconId: "conditionIcon",
  });

  initCustomSelect({
    containerId: "customPriceContainer",
    buttonId: "customPriceButton",
    optionsId: "customPriceOptions",
    textId: "customPriceText",
    inputId: "selectedPriceInput",
    iconId: "priceIcon",
  });
});
