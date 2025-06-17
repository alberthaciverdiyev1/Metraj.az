document.addEventListener("DOMContentLoaded", function () {
    console.log("ssssss")
    document.getElementById('login-btn').addEventListener('click', async function (e) {
        e.preventDefault();
        const formContainer = document.getElementById('login-form');

        const form = e.target;
        const data = {
            email: formContainer.querySelector('input[name="email"]').value,
            password: formContainer.querySelector('input[name="password"]').value,
        };
        console.log(data)
        const errorMsgEl = document.getElementById('error-message');
        errorMsgEl.innerText = '';

        try {
            const response = await fetch('/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data),
            });

            const result = await response.json();

            if (response.status === 201 || result.status === 201) {
                alert(result.message || 'Login successfully!');
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
})
