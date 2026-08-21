document.addEventListener("DOMContentLoaded", function () {
    console.log("Login page initialized");

    const loginBtn = document.getElementById('login-btn');
    const loginForm = document.getElementById('login-form');

    const emailInput = loginForm.querySelector('input[name="email"]');
    const passwordInput = loginForm.querySelector('input[name="password"]');

    // inputların altına error span əlavə edək (əgər yoxdursa)
    let emailError = document.getElementById("email-error");
    if (!emailError) {
        emailError = document.createElement("span");
        emailError.id = "email-error";
        emailError.className = "text-red-500 text-sm block mt-1";
        emailInput.insertAdjacentElement("afterend", emailError);
    }

    let passwordError = document.getElementById("password-error");
    if (!passwordError) {
        passwordError = document.createElement("span");
        passwordError.id = "password-error";
        passwordError.className = "text-red-500 text-sm block mt-1";
        passwordInput.insertAdjacentElement("afterend", passwordError);
    }

    // Toast göstərəcək funksiya
    function showToast(message, type = "success") {
        const toast = document.createElement("div");
        toast.className = `fixed top-5 right-5 px-4 py-2 rounded-xl shadow-lg text-white z-[9999] transition-all
            ${type === "success" ? "bg-green-500" : "bg-red-500"}`;
        toast.innerText = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    if (loginBtn && loginForm) {
        loginBtn.addEventListener('click', async function (e) {
            e.preventDefault();

            // error mesajlarını sıfırla
            emailError.textContent = "";
            passwordError.textContent = "";

            const data = {
                email: emailInput.value.trim(),
                password: passwordInput.value
            };

            let valid = true;

            if (!data.email) {
                emailError.textContent = "Email boş ola bilməz";
                valid = false;
            }

            if (!data.password) {
                passwordError.textContent = "Şifrə boş ola bilməz";
                valid = false;
            } else if (data.password.length < 6) {
                passwordError.textContent = "Şifrə minimum 6 simvol olmalıdır";
                valid = false;
            }

            if (!valid) return; // səhv varsa POST getmir

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
                    if (response.ok) {
    console.log("Login successful:", result);
    showToast(result.message || "Uğurla daxil oldunuz ✅", "success");

    // Backenddən gələn rol və token (əgər varsa)
    localStorage.setItem("userRole", result.role || "user"); 
    localStorage.setItem("isLoggedIn", "true");

    setTimeout(() => {
        window.location.href = result.redirect || '/';
    }, 1500);
}

                } else {
                    console.log("Login error:", result);
                    showToast(result.message || "Email və ya şifrə səhvdir ❌", "error");

                    // serverdən field errors gəlirsə input altına yaz
                    if (result.errors) {
                        if (result.errors.email) {
                            emailError.textContent = result.errors.email.join(", ");
                        }
                        if (result.errors.password) {
                            passwordError.textContent = result.errors.password.join(", ");
                        }
                    }
                }

            } catch (error) {
                console.error('Network error:', error);
                showToast("Şəbəkə xətası baş verdi. Yenidən cəhd edin ❌", "error");
            }
        });
    }
});
