import { getPropertiesList } from "./components/property.js";
import { propertyCard } from "./cards/property.js";
import { propertySkeletonCard } from "./cards/propertySkeleton.js"; 

document.addEventListener('DOMContentLoaded', function () {
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
                const triangleIndicator = b.parentElement.querySelector(".triangle-indicator");
                if (triangleIndicator) {
                    triangleIndicator.classList.add("hidden");
                }
            });

            btn.classList.remove(...inactiveButtonBaseClasses);
            btn.classList.remove(...inactiveButtonHoverClasses);
            btn.classList.add(...activeButtonClasses);
            const triangleIndicator = btn.parentElement.querySelector(".triangle-indicator");
            if (triangleIndicator) {
                triangleIndicator.classList.remove("hidden");
            }
        });
    });

    const filterBtn = document.getElementById('filterButton');
    const modal = document.getElementById('filterModal');

    if (filterBtn && modal) {
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
    }

    const selectWrappers = document.querySelectorAll('.select-wrapper');
    selectWrappers.forEach(wrapper => {
        const select = wrapper.querySelector('.custom-select');
        if (select) { 
            select.addEventListener('focus', () => {
                wrapper.classList.add('focused');
            });
            select.addEventListener('blur', () => {
                wrapper.classList.remove('focused');
            });
        }
    });
}); 

const downPaymentInput = document.getElementById('downPayment');
const downPaymentPercentInput = document.getElementById('downPaymentPercent');

if (downPaymentInput) {
    downPaymentInput.addEventListener('input', function () {
        const total = parseFloat(document.getElementById('totalAmount').value) || 0;
        const downPayment = parseFloat(this.value) || 0;
        const percent = (downPayment / total * 100).toFixed(2);
        if (document.getElementById('downPaymentPercent')) {
            document.getElementById('downPaymentPercent').value = percent;
        }
    });
}

if (downPaymentPercentInput) {
    downPaymentPercentInput.addEventListener('input', function () {
        const total = parseFloat(document.getElementById('totalAmount').value) || 0;
        const percent = parseFloat(this.value) || 0;
        const downPayment = (total * percent / 100).toFixed(2);
        if (document.getElementById('downPayment')) {
            document.getElementById('downPayment').value = downPayment;
        }
    });
}

function calculatePayment() {
    const totalInput = document.getElementById('totalAmount');
    const downInput = document.getElementById('downPayment');
    const interestInput = document.getElementById('interestRate');
    const yearsInput = document.getElementById('amortizationPeriod');
    const taxInput = document.getElementById('propertyTax');
    const insuranceInput = document.getElementById('homeInsurance');
    const paymentDisplay = document.getElementById('paymentDisplay');
    const modalPayment = document.getElementById('modalPayment');
    const modal = document.getElementById('modal');

    if (!totalInput || !downInput || !interestInput || !yearsInput || !taxInput || !insuranceInput || !paymentDisplay || !modalPayment || !modal) {
        console.warn("One or more mortgage calculator elements not found.");
        return;
    }

    const total = parseFloat(totalInput.value) || 0;
    const down = parseFloat(downInput.value) || 0;
    const interest = parseFloat(interestInput.value) / 100 / 12;
    const years = parseInt(yearsInput.value);
    const months = years * 12;
    const tax = (parseFloat(taxInput.value) || 0) / 12;
    const insurance = (parseFloat(insuranceInput.value) || 0) / 12;

    let principal = total - down;
    let monthly = 0;

    if (interest > 0 && months > 0) {
        monthly = (principal * interest * Math.pow(1 + interest, months)) / (Math.pow(1 + interest, months) - 1);
    } else if (months > 0) {
        monthly = principal / months;
    }

    const payment = Math.round(monthly + tax + insurance);

    paymentDisplay.textContent = `$${payment.toLocaleString()}`;
    modalPayment.textContent = `$${payment.toLocaleString()}`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    const modal = document.getElementById('modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}

function resetForm() {
    if (document.getElementById('totalAmount')) document.getElementById('totalAmount').value = '100000';
    if (document.getElementById('downPayment')) document.getElementById('downPayment').value = '20000';
    if (document.getElementById('downPaymentPercent')) document.getElementById('downPaymentPercent').value = '20';
    if (document.getElementById('interestRate')) document.getElementById('interestRate').value = '5';
    if (document.getElementById('amortizationPeriod')) document.getElementById('amortizationPeriod').value = '0';
    if (document.getElementById('propertyTax')) document.getElementById('propertyTax').value = '3000';
    if (document.getElementById('homeInsurance')) document.getElementById('homeInsurance').value = '1200';
    if (document.getElementById('paymentDisplay')) document.getElementById('paymentDisplay').textContent = '$0';
}

if (typeof AOS !== 'undefined') { 
    AOS.init({
        duration: 800,
        once: true
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.tab-btn');
    const contents = document.querySelectorAll('.tab-content');

    if (tabs.length > 0 && contents.length > 0) { 
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(btn => btn.classList.remove('active'));
                contents.forEach(content => content.classList.remove('active'));

                tab.classList.add('active');
                const tabId = tab.getAttribute('data-tab');
                const targetContent = document.getElementById(tabId);
                if (targetContent) {
                    targetContent.classList.add('active');
                }

                if (typeof AOS !== 'undefined') {
                    AOS.refresh();
                }
            });
        });
    }
});

document.addEventListener("DOMContentLoaded", async function () {
    function animateText(element) {
        if (!element) return; 
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

    const homePropertyContainer = document.getElementById('propertyContainer');
    const homePropertiesLoadingOverlay = document.getElementById('homePropertiesLoadingOverlay');

    function renderSkeletons(container, count) {
        if (!container) return;
        let skeletonsHtml = '';
        for (let i = 0; i < count; i++) {
            skeletonsHtml += propertySkeletonCard();
        }
        container.innerHTML = skeletonsHtml;
    }

    async function properties() {
        if (!homePropertyContainer) {
            console.error("Ana səhifə əmlak konteyneri tapılmadı.");
            return;
        }

        if (homePropertiesLoadingOverlay) {
            homePropertiesLoadingOverlay.style.display = 'flex';
        }

        renderSkeletons(homePropertyContainer, 4); 

        let processedProperties = [];

        try {
            const fetchedApiResponse = await getPropertiesList();
            let html = '';

            Object.entries(fetchedApiResponse).forEach(
                ([key, value]) => {
                    if (value.length > 0) {
                        html += `
                          <div class="col-span-full flex justify-between items-center px-6">
                            <h2 class="text-2xl font-semibold text-gray-900 tracking-wide">
                              ${key}
                            </h2>
                            <a href="/property" class="text-orange-400 text-lg font-medium hover:underline hover:text-orange-500 transition-colors duration-200">
                              Daha Çox →
                            </a>
                          </div>`;

                        value.forEach(property => {
                            html += propertyCard(property);
                        });
                    }
                }
            )

            
            homePropertyContainer.innerHTML = html;

        } catch (error) {
            console.error("Ana səhifə əmlakları çəkilərkən xəta:", error);
            homePropertyContainer.innerHTML = '<p class="text-red-500 text-center col-span-full">Elanlar yüklənərkən xəta baş verdi.</p>';
        } finally {
            if (homePropertiesLoadingOverlay) {
                homePropertiesLoadingOverlay.style.display = 'none';
            }
        }
    }

    await properties(); 
});