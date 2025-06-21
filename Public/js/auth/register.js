document.addEventListener("DOMContentLoaded", function () {
    console.log("Register page loaded");

    function toggleFields() {
        const role = document.getElementById("role").value;
        const agencyName = document.getElementById("agencyNameField");
        const phoneField = document.getElementById("phoneField");
        const phoneInput = document.getElementById("phone");

        if (role === "agent") {
            agencyName.classList.remove("hidden");
            phoneField.classList.remove("hidden");
            phoneInput.setAttribute("required", "required");
        } else {
            agencyName.classList.add("hidden");
            phoneField.classList.add("hidden");
            phoneInput.removeAttribute("required");
        }
    }

    toggleFields();

    const form = document.querySelector("form");
    if (form) {
        form.addEventListener("submit", async function (e) {
            e.preventDefault();

            const formData = new FormData(form);
            const data = {
                role: formData.get("role"),
                name: formData.get("full-name"),
                agency_name: formData.get("agency_name"),
                email: formData.get("email"),
                password: formData.get("password"),
                password_confirmation: formData.get("password_confirmation"),
                phone: formData.get("phone"),
                _token: formData.get("_token")
            };

            const termsAccepted = document.getElementById("terms").checked;
            const errorMsgEl = document.createElement('div');
            errorMsgEl.className = 'text-red-500 text-sm mt-2';
            
            document.querySelectorAll('.error-message').forEach(el => el.remove());

            if (!termsAccepted) {
                errorMsgEl.textContent = 'You must agree to the terms and privacy policy.';
                document.getElementById("terms").parentNode.appendChild(errorMsgEl);
                return;
            }

            try {
                const response = await fetch('/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': data._token
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (response.ok) {
                    alert(result.message || 'Account created successfully!');
                    window.location.href = '/login';
                } else {
                    if (result.errors) {
                        for (const [field, messages] of Object.entries(result.errors)) {
                            const input = document.querySelector(`[name="${field}"]`);
                            if (input) {
                                const errorEl = document.createElement('div');
                                errorEl.className = 'error-message text-red-500 text-sm mt-1';
                                errorEl.textContent = messages.join(', ');
                                input.parentNode.appendChild(errorEl);
                            }
                        }
                    } else if (result.message) {
                        const generalError = document.createElement('div');
                        generalError.className = 'error-message text-red-500 text-sm mt-4';
                        generalError.textContent = result.message;
                        form.insertBefore(generalError, form.firstChild);
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                const networkError = document.createElement('div');
                networkError.className = 'error-message text-red-500 text-sm mt-4';
                networkError.textContent = 'A network error occurred. Please try again.';
                form.insertBefore(networkError, form.firstChild);
            }
        });
    }

    const roleSelect = document.getElementById("role");
    if (roleSelect) {
        roleSelect.addEventListener("change", toggleFields);
    }
});