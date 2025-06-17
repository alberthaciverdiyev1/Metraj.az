    document.addEventListener("DOMContentLoaded", () => {
        const btn = document.getElementById('dropdownButton');
        const menu = document.getElementById('dropdownMenu');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });

        window.addEventListener('click', (e) => {
            if (!btn.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });

        const dropdownLinks = menu.querySelectorAll('a');

        dropdownLinks.forEach(link => {
            link.addEventListener('click', () => {
                dropdownLinks.forEach(l => {
                    l.classList.remove('bg-orange-400', 'text-white');
                    l.classList.add('text-gray-700');
                });
                link.classList.add('bg-orange-400', 'text-white');
                link.classList.remove('text-gray-700');
            });
        });
    });
