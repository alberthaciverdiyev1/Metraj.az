

    document.addEventListener('DOMContentLoaded', function() {
        const toggleButtons = document.querySelectorAll(".toggle-btn");
        const inactiveButtonBaseClasses = ["bg-white", "text-black", "border", "border-gray-300"];
        const inactiveButtonHoverClasses = ["hover:bg-gray-100", "hover:text-black"]; 
        const activeButtonClasses = ["bg-red-400", "text-white"];

        toggleButtons.forEach(btn => {
          
            if (!btn.classList.contains("bg-red-400")) {
                btn.classList.add(...inactiveButtonHoverClasses);
            }

            btn.addEventListener("click", () => {
                toggleButtons.forEach(b => {
                    b.classList.remove(...activeButtonClasses);
                    b.classList.add(...inactiveButtonBaseClasses);
                    b.classList.add(...inactiveButtonHoverClasses);
                    b.parentElement.querySelector(".triangle-indicator").classList.add("hidden");
                });

                btn.classList.remove(...inactiveButtonBaseClasses);
                btn.classList.remove(...inactiveButtonHoverClasses); 
                btn.classList.add(...activeButtonClasses);
                btn.parentElement.querySelector(".triangle-indicator").classList.remove("hidden");
            });
        });

        const filterBtn = document.getElementById('filterButton');
        const modal = document.getElementById('filterModal');

        filterBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (modal.classList.contains('invisible')) {
                modal.classList.remove('invisible', 'opacity-0', '-translate-y-3');
                modal.classList.add('visible', 'opacity-100', 'translate-y-0');
            } else {
                modal.classList.remove('visible', 'opacity-100', 'translate-y-0');
                modal.classList.add('invisible', 'opacity-0', '-translate-y-3');
            }
        });

        document.addEventListener('click', (e) => {
            if (modal.classList.contains('visible') && !modal.contains(e.target) && !filterBtn.contains(e.target)) {
                modal.classList.remove('visible', 'opacity-100', 'translate-y-0');
                modal.classList.add('invisible', 'opacity-0', '-translate-y-3');
            }
        });

        const selectWrappers = document.querySelectorAll('.select-wrapper');
        selectWrappers.forEach(wrapper => {
            const select = wrapper.querySelector('.custom-select');
            select.addEventListener('focus', () => {
                wrapper.classList.add('focused');
            });
            select.addEventListener('blur', () => {
                wrapper.classList.remove('focused');
            });
        });
    });