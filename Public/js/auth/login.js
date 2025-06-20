document.addEventListener("DOMContentLoaded", function () {
    console.log("Login page initialized");

    const loginBtn = document.getElementById('login-btn');
    const loginForm = document.getElementById('login-form');
    
    if (loginBtn && loginForm) {
        loginBtn.addEventListener('click', async function (e) {
            e.preventDefault();
            
            const data = {
                email: loginForm.querySelector('input[name="email"]').value.trim(),
                password: loginForm.querySelector('input[name="password"]').value
            };

            console.log("Login attempt with:", data);
            
            const errorMsgEl = document.getElementById('error-message');
            errorMsgEl.innerText = '';
            errorMsgEl.classList.remove('active-error'); 
            
            if (!data.email || !data.password) {
                errorMsgEl.innerText = 'Please fill in all fields';
                errorMsgEl.classList.add('active-error');
                return;
            }

            try {
                const response = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(data),
                });

                const result = await response.json();

                if (response.ok) {
                    console.log("Login successful:", result);
                    
                    if (result.message) {
                        alert(result.message);
                    }
                    
                    window.location.href = result.redirect || '/';
                } else {
                    console.log("Login error:", result);
                    
                    if (result.errors) {
                        const errorMessages = Object.values(result.errors).flat();
                        errorMsgEl.innerText = errorMessages.join('\n');
                    } else if (result.message) {
                        errorMsgEl.innerText = result.message;
                    } else {
                        errorMsgEl.innerText = 'Invalid email or password';
                    }
                    
                    errorMsgEl.classList.add('active-error');
                }

            } catch (error) {
                console.error('Network error:', error);
                errorMsgEl.innerText = 'A network error occurred. Please try again.';
                errorMsgEl.classList.add('active-error');
            }
        });
    }
});