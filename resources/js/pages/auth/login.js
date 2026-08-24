/* Login səhifəsi — şifrə göstər/gizlə & AJAX giriş (login.blade.php-dən çıxarılıb) */
document.addEventListener('DOMContentLoaded', function () {
    const i18n = window.loginConfig?.i18n || {};

    const loginForm = document.getElementById('loginForm');
    const submitBtn = document.getElementById('loginSubmitBtn');
    const passwordInput = document.getElementById('login_password');
    const togglePasswordBtn = document.getElementById('togglePasswordBtn');
    const togglePasswordIcon = document.getElementById('togglePasswordIcon');
    const emailError = document.getElementById('email_error');
    const passwordError = document.getElementById('password_error');

    // Toggle Password Visibility
    if (togglePasswordBtn && passwordInput && togglePasswordIcon) {
        togglePasswordBtn.addEventListener('click', function () {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            togglePasswordIcon.className = isPassword ? 'bi bi-eye-slash text-lg' : 'bi bi-eye text-lg';
        });
    }

    // AJAX Login Handler
    if (loginForm) {
        loginForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            // Clear errors
            if (emailError) { emailError.textContent = ''; emailError.classList.add('hidden'); }
            if (passwordError) { passwordError.textContent = ''; passwordError.classList.add('hidden'); }

            const originalBtnHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span> <span>' + (i18n.checking || 'Yoxlanılır...') + '</span>';

            const formData = new FormData(loginForm);

            try {
                const response = await fetch(loginForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    if (window.Metraj && window.Metraj.toast) {
                        window.Metraj.toast(data.message || i18n.success || 'Uğurla daxil oldunuz!', 'success');
                    }
                    submitBtn.innerHTML = '<i class="bi bi-check2-circle text-lg"></i> <span>' + (i18n.loggedIn || 'Daxil olundu!') + '</span>';

                    setTimeout(() => {
                        window.location.href = data.redirect || '/';
                    }, 800);
                } else {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnHtml;

                    const msg = data.message || i18n.invalid || 'E-poçt və ya şifrə yanlışdır.';
                    if (window.Metraj && window.Metraj.toast) {
                        window.Metraj.toast(msg, 'error');
                    }

                    if (data.errors) {
                        if (data.errors.email && emailError) {
                            emailError.textContent = data.errors.email[0];
                            emailError.classList.remove('hidden');
                        }
                        if (data.errors.password && passwordError) {
                            passwordError.textContent = data.errors.password[0];
                            passwordError.classList.remove('hidden');
                        }
                    }
                }
            } catch (err) {
                console.error(err);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
                if (window.Metraj && window.Metraj.toast) {
                    window.Metraj.toast(i18n.network || 'Şəbəkə xətası baş verdi. Yenidən cəhd edin.', 'error');
                }
            }
        });
    }
});
