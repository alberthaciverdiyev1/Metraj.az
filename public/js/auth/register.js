document.addEventListener("DOMContentLoaded", function () {
    console.log("Register page");

    document.getElementById('register-submit-btn').addEventListener('click', async function (e) {
        e.preventDefault();

        const formContainer = document.getElementById('register-form');

        const data = {
            name: formContainer.querySelector('input[name="name"]').value,
            email: formContainer.querySelector('input[name="email"]').value,
            password: formContainer.querySelector('input[name="password"]').value,
            password_confirmation: formContainer.querySelector('input[name="password_confirmation"]').value,
        };

        const termsAccepted = formContainer.querySelector('#terms').checked;

        const errorMsgEl = document.getElementById('error-message');
        errorMsgEl.innerText = '';

        if (!termsAccepted) {
            errorMsgEl.innerText = 'You must agree to the terms and privacy policy.';
            return;
        }

        try {
            const response = await fetch('/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data),
            });

            const result = await response.json();

            if (response.status === 201 || result.status === 201) {
                alert(result.message || 'Account created successfully!');
             //   window.location.href = '/login';
            } else {
                if (result.errors) {
                    errorMsgEl.innerText = Object.values(result.errors).flat().join('\n');
                } else if (result.message) {
                    errorMsgEl.innerText = result.message;
                } else {
                    errorMsgEl.innerText = 'An error occurred. Please try again.';
                }
            }

        } catch (error) {
            errorMsgEl.innerText = 'A network error occurred. Please try again.';
            console.error('Fetch error:', error);
        }
    });
});
