document.addEventListener('DOMContentLoaded', function () {
    const callButton = document.getElementById('call-button');
    const phoneNumbersContainer = document.getElementById('phone-numbers-container');
    const phoneModal = document.getElementById('phone-modal');
    const closeButton = document.querySelector('.close-button');
    const phoneOptionsContainer = document.getElementById('phone-options-container');


    const sellerSettings = {
        phone: ["+994501234567", "+994709876543"],
    };

    let phoneNumbers = [];

    if (sellerSettings.phone) {
        if (Array.isArray(sellerSettings.phone)) {
            phoneNumbers = sellerSettings.phone;
        } else {
            phoneNumbers = [sellerSettings.phone];
        }
    }
    phoneNumbersContainer.innerHTML = '';


    if (phoneNumbers.length > 0) {
        phoneNumbers.forEach(number => {
            const p = document.createElement('p');
            const a = document.createElement('a');
            a.href = `tel:${number}`;
            a.className = 'phone flex items-center gap-2 text-[#E4893A] hover:text-orange-500';
            a.innerHTML = `<svg  xmlns="http://www.w3.org/2000/svg"  width="20"  height="20"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-phone-call text-[#E4893A]"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /><path d="M15 7a2 2 0 0 1 2 2" /><path d="M15 3a6 6 0 0 1 6 6" /></svg>
            ${number}`;
            p.appendChild(a);
            phoneNumbersContainer.appendChild(p);
        });
    }

    callButton.addEventListener('click', function () {
        if (phoneNumbers.length === 1) {
            window.location.href = `tel:${phoneNumbers[0]}`;
        } else if (phoneNumbers.length > 1) {
            openPhoneModal(phoneNumbers);
        } else {
            alert("No phone numbers available for this seller.");
        }
    });

    closeButton.addEventListener('click', function () {
        phoneModal.style.display = 'none';
    });

    window.addEventListener('click', function (event) {
        if (event.target == phoneModal) {
            phoneModal.style.display = 'none';
        }
    });

    function openPhoneModal(numbers) {
        phoneOptionsContainer.innerHTML = '';
        numbers.forEach(number => {
            const button = document.createElement('a');
            button.href = `tel:${number}`;
            button.className = 'phone-option-btn';
            button.textContent = number;
            phoneOptionsContainer.appendChild(button);
        });
        phoneModal.style.display = 'block';
    }


});