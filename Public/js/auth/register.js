document.addEventListener("DOMContentLoaded", function () {
    const roleSelect = document.getElementById("role");
    const agencyNameField = document.getElementById("agencyNameField");
    const agencyPhoneField = document.getElementById("agencyPhoneField");
    const phoneField = document.getElementById("phoneField");
    const form = document.getElementById("registerForm");

    function toggleFields() {
        const role = roleSelect.value;
        const isAgent = role === "agent";
        agencyNameField.classList.toggle("hidden", !isAgent);
        agencyPhoneField.classList.toggle("hidden", !isAgent);
        phoneField.classList.toggle("hidden", !isAgent);
    }

    toggleFields();
    roleSelect.addEventListener("change", toggleFields);

    function showError(input, message) {
        let errorElem = input.nextElementSibling;
        if (!errorElem || !errorElem.classList.contains("error-message")) {
            errorElem = document.createElement("div");
            errorElem.classList.add("error-message");
            input.parentNode.appendChild(errorElem);
        }
        errorElem.textContent = message;
    }

    function clearErrors() {
        document.querySelectorAll(".error-message").forEach(e => e.remove());
    }

    function showToast(message, type = "success") {
        const toast = document.createElement("div");
        toast.className = "toast fixed top-4 right-4 px-4 py-2 rounded shadow-lg text-white font-medium";
        toast.style.backgroundColor = type === "success" ? "#28a745" : "#dc3545"; // yaşıl/qırmızı
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = "0";
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }


    form.addEventListener("submit", async function (e) {
        e.preventDefault();
        clearErrors();

        const formData = new FormData(form);
        const role = formData.get("role");
        const name = formData.get("full-name");
        const email = formData.get("email");
        const password = formData.get("password");
        const password_confirmation = formData.get("password_confirmation");
        const agency_name = formData.get("agency_name");
        const agency_phone = formData.get("agency_phone");
        const phone = formData.get("phone");

        let hasError = false;

        if (!role) { showError(roleSelect, "Role seçmək vacibdir."); hasError = true; }
        if (!name) { showError(document.getElementById("full-name"), "Ad vacibdir."); hasError = true; }
        if (!email) { showError(document.getElementById("email"), "Email vacibdir."); hasError = true; }
        if (!password) { showError(document.getElementById("password"), "Parol vacibdir."); hasError = true; }
        if (!password_confirmation) { showError(document.getElementById("password_confirmation"), "Parolu təsdiqləyin."); hasError = true; }
        if (password && password_confirmation && password !== password_confirmation) {
            showError(document.getElementById("password_confirmation"), "Parollar eyni deyil.");
            hasError = true;
        }
        if (role === "agent") {
            if (!agency_name) { showError(document.getElementById("agency_name"), "Agentlik adı vacibdir."); hasError = true; }
            if (!agency_phone) { showError(document.getElementById("agency_phone"), "Agentlik telefon nömrəsi vacibdir."); hasError = true; }
            if (!phone) { showError(document.getElementById("phone"), "Telefon nömrəsi vacibdir."); hasError = true; }
        }

        if (!document.getElementById("terms").checked) {
            showToast("Şərtləri qəbul etməlisiniz!", "error");
            hasError = true;
        }

        if (hasError) return;

        const data = { role, name, agency_name, email, password, password_confirmation, phone };

        try {
            const response = await fetch("/register", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                showToast("Qeydiyyat uğurla tamamlandı!", "success");
                setTimeout(() => {
                    window.location.href = "/profile"; // avtomatik yönləndirmə
                }, 1500); // 1.5 saniyə sonra yönləndirir
            } else {
                if (result.errors) {
                    Object.keys(result.errors).forEach(key => {
                        const input = document.getElementById(key) || roleSelect;
                        showError(input, result.errors[key][0]);
                    });
                }
                showToast("Qeydiyyat zamanı xəta baş verdi.", "error");
            }
        } catch (error) {
            showToast("Serverlə əlaqə mümkün olmadı.", "error");
        }
    });

});