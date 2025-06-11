document.addEventListener("DOMContentLoaded", function () {
    console.log("Register page");
    document.getElementById('register-form').addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = e.target;
        const data = {
            name: form.name.value,
            email: form.email.value,
            password: form.password.value,
            password_confirmation: form.password_confirmation.value,
            _token: form.querySelector('input[name="_token"]').value
        };

        const errorMsgEl = document.getElementById('error-message');
        errorMsgEl.innerText = '';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data),
            });

            const result = await response.json();
            if (result.status === 201) {
                alert(result.message);

            } else {
                if (result.errors) {
                    errorMsgEl.innerText = Object.values(result.errors).flat().join('\n');
                } else if (result.message) {
                    errorMsgEl.innerText = result.message;
                } else {
                    errorMsgEl.innerText = 'An error occurred. Please try again later.';
                }
            }

        } catch (error) {
            errorMsgEl.innerText = 'An error occurred. Please try again later.';
            console.error('Fetch error:', error);
        }
    });
})
