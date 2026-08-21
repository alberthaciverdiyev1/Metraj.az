let currentImageIndex = 0;
let images = typeof imagesData !== 'undefined' ? imagesData : [];
let slideshowInterval;

const modal = document.getElementById('modal');
const modalImage = document.getElementById('modal-image');
const counter = document.getElementById('counter');
const thumbnailsContainer = document.getElementById('thumbnails');

if (images.length > 0) {
    const galleryMain = document.querySelector('.gallery-main');
    if (galleryMain) {
        galleryMain.onclick = () => openModal(0);
    }
    
    const galleryThumbnails = document.querySelectorAll('.gallery-thumbnails figure');
    galleryThumbnails.forEach((figure, index) => {
        figure.onclick = () => openModal(index + 1);
    });

    const moreImagesOverlay = document.querySelector('.more-images-overlay');
    if (moreImagesOverlay) {
        moreImagesOverlay.onclick = () => openModal(10); 
    }

    const modalThumbnails = document.querySelectorAll('#thumbnails img');
    modalThumbnails.forEach((thumb, index) => {
        thumb.onclick = () => openModal(index);
    });
}

function openModal(index) {
    currentImageIndex = index;
    updateModalContent();
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    modal.style.display = 'none';
    document.body.style.overflow = 'auto';
    stopSlideshow();
}

function prevImage() {
    currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
    updateModalContent();
}

function nextImage() {
    currentImageIndex = (currentImageIndex + 1) % images.length;
    updateModalContent();
}

function updateModalContent() {
    if (images.length === 0) {
        modalImage.src = '';
        counter.textContent = '0/0';
        return;
    }
    modalImage.src = images[currentImageIndex];
    counter.textContent = `${currentImageIndex + 1}/${images.length}`;
    
    modalImage.style.width = 'auto'; 
    modalImage.style.height = '80vh'; 
    modalImage.style.maxWidth = '90vw';

    const allThumbnails = thumbnailsContainer.querySelectorAll('img');
    allThumbnails.forEach((thumb, index) => {
        if (index === currentImageIndex) {
            thumb.classList.add('active');
            thumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        } else {
            thumb.classList.remove('active');
        }
    });
}

function startSlideshow() {
    if (slideshowInterval) {
        stopSlideshow();
        return;
    }
    slideshowInterval = setInterval(() => {
        nextImage();
    }, 3000);
    
    const playButton = document.querySelector('.modal-actions .bi-play-fill');
    const pauseButton = document.querySelector('.modal-actions .bi-pause-fill');
    if (playButton && pauseButton) {
        playButton.classList.add('d-none');
        pauseButton.classList.remove('d-none');
    }
}

function stopSlideshow() {
    clearInterval(slideshowInterval);
    slideshowInterval = null;
    
    const playButton = document.querySelector('.modal-actions .bi-play-fill');
    const pauseButton = document.querySelector('.modal-actions .bi-pause-fill');
    if (playButton && pauseButton) {
        playButton.classList.remove('d-none');
        pauseButton.classList.add('d-none');
    }
}

function toggleFullscreen() {
    if (!document.fullscreenElement) {
        modal.requestFullscreen().catch(err => {
            console.error(`Error attempting to enable full-screen mode: ${err.message}`);
        });
    } else {
        document.exitFullscreen();
    }
}

function toggleThumbnails() {
    thumbnailsContainer.classList.toggle('d-none');
}

function shareImage() {
    if (navigator.share && images[currentImageIndex]) {
        navigator.share({
            title: document.title,
            url: images[currentImageIndex]
        }).catch(console.error);
    } else {
        if (images[currentImageIndex]) {
            navigator.clipboard.writeText(images[currentImageIndex]).then(() => {
                alert('Image URL copied to clipboard!');
            }).catch(() => {
                console.error('Failed to copy to clipboard');
            });
        }
    }
}

modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        closeModal();
    }
});

document.addEventListener('keydown', (e) => {
    if (modal.style.display === 'block') {
        switch(e.key) {
            case 'Escape':
                closeModal();
                break;
            case 'ArrowLeft':
                prevImage();
                break;
            case 'ArrowRight':
                nextImage();
                break;
            case ' ': 
                e.preventDefault();
                if (slideshowInterval) {
                    stopSlideshow();
                } else {
                    startSlideshow();
                }
                break;
        }
    }
});

let startX = 0;
let endX = 0;

modal.addEventListener('touchstart', e => {
    startX = e.changedTouches[0].screenX;
});

modal.addEventListener('touchend', e => {
    endX = e.changedTouches[0].screenX;
    handleSwipe();
});

function handleSwipe() {
    if (startX - endX > 50) {
        nextImage(); 
    } else if (endX - startX > 50) {
        prevImage(); 
    }
}