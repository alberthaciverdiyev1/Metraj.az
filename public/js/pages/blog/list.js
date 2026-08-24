/* Blog lista səhifəsi — Grid/List görünüş dəyişdirici (blog/list.blade.php-dən çıxarılıb) */
document.addEventListener('DOMContentLoaded', function() {
    const gridBtn = document.getElementById('gridViewBtn');
    const listBtn = document.getElementById('listViewBtn');
    const container = document.getElementById('blogContainer');

    if (!container || !gridBtn || !listBtn) return;

    function applyView(mode) {
        if (mode === 'list') {
            container.className = 'grid grid-cols-1 gap-5';

            // Adjust card classes for list mode
            container.querySelectorAll('.blog-card').forEach(card => {
                card.classList.add('sm:flex-row');
                const imgWrap = card.querySelector('.blog-card-image');
                if (imgWrap) {
                    imgWrap.className = 'blog-card-image relative overflow-hidden aspect-[16/10] sm:aspect-auto sm:w-72 sm:min-w-[280px] bg-orange-50 shrink-0';
                }
            });

            listBtn.className = 'px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold transition duration-200 bg-white text-orange-500 shadow-sm';
            gridBtn.className = 'px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold transition duration-200 text-gray-600 hover:text-gray-900 hover:bg-white/50';
        } else {
            container.className = 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6';

            container.querySelectorAll('.blog-card').forEach(card => {
                card.classList.remove('sm:flex-row');
                const imgWrap = card.querySelector('.blog-card-image');
                if (imgWrap) {
                    imgWrap.className = 'blog-card-image relative overflow-hidden aspect-[16/10] bg-orange-50 shrink-0';
                }
            });

            gridBtn.className = 'px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold transition duration-200 bg-white text-orange-500 shadow-sm';
            listBtn.className = 'px-3.5 py-2 rounded-xl text-xs sm:text-sm font-bold transition duration-200 text-gray-600 hover:text-gray-900 hover:bg-white/50';
        }
        localStorage.setItem('metraj_blog_view', mode);
    }

    gridBtn.addEventListener('click', () => applyView('grid'));
    listBtn.addEventListener('click', () => applyView('list'));

    const savedView = localStorage.getItem('metraj_blog_view') || 'grid';
    if (savedView === 'list') {
        applyView('list');
    }
});
