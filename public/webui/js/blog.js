document.addEventListener('DOMContentLoaded', function() {
    const gridViewBtn = document.getElementById('gridViewBtn');
    const listViewBtn = document.getElementById('listViewBtn');
    const blogCards = document.querySelector('.blog-cards');
    
    function initView() {
        blogCards.classList.add('grid-view');
        gridViewBtn.classList.add('active-filter');
        listViewBtn.classList.remove('active-filter');
        listViewBtn.querySelector('i').style.color = 'var(--icon-grey)';
    }
    
    function switchToGridView() {
        blogCards.classList.remove('list-view');
        blogCards.classList.add('grid-view');
        
        gridViewBtn.classList.add('active-filter');
        listViewBtn.classList.remove('active-filter');
        listViewBtn.querySelector('i').style.color = 'var(--icon-grey)';
    }
    
    function switchToListView() {
        blogCards.classList.remove('grid-view');
        blogCards.classList.add('list-view');
        
        listViewBtn.classList.add('active-filter');
        gridViewBtn.classList.remove('active-filter');
        listViewBtn.querySelector('i').style.color = 'white';
    }
    
    gridViewBtn.addEventListener('click', switchToGridView);
    listViewBtn.addEventListener('click', switchToListView);
    
    initView();
});