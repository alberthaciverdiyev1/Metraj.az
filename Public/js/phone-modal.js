document.addEventListener('DOMContentLoaded', function () {
    const callButton = document.getElementsByClassName('call-button');
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
            a.className = 'phone';
            a.innerHTML = `<i class="bi bi-telephone"></i> ${number}`;
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