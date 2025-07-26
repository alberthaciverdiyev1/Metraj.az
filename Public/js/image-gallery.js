let currentImageIndex = 0;
let images = [];
let slideshowInterval;

const modal = document.getElementById('modal');
const modalImage = document.getElementById('modal-image');
const counter = document.getElementById('counter');
const thumbnailsContainer = document.getElementById('thumbnails');

document.addEventListener('DOMContentLoaded', () => {
    const galleryImages = document.querySelectorAll('#listing-detail-gallery .gallery-grid figure img');
    galleryImages.forEach(img => {
        images.push(img.src);
    });

    const modalThumbnails = document.querySelectorAll('#thumbnails img');
    modalThumbnails.forEach((thumb, index) => {
        thumb.onclick = () => openModal(index);
    });
});

function openModal(index) {
    currentImageIndex = index;
    updateModalContent();
    modal.style.display = 'block';
}

function closeModal() {
    modal.style.display = 'none';
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
    modalImage.style.width = '800px';
    modalImage.style.height = '800px';


    const allThumbnails = thumbnailsContainer.querySelectorAll('img');
    allThumbnails.forEach((thumb, index) => {
        if (index === currentImageIndex) {
            thumb.classList.add('active');
            thumb.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            thumb.classList.remove('active');
        }
    });
}

function startSlideshow() {
    if (slideshowInterval) {
        stopSlideshow();
    }
    slideshowInterval = setInterval(() => {
        nextImage();
    }, 3000);
    document.querySelector('.modal-actions .bi-play-fill').classList.add('d-none');
    document.querySelector('.modal-actions .bi-pause-fill').classList.remove('d-none');
}

function stopSlideshow() {
    clearInterval(slideshowInterval);
    slideshowInterval = null;
    document.querySelector('.modal-actions .bi-play-fill').classList.remove('d-none');
    document.querySelector('.modal-actions .bi-pause-fill').classList.add('d-none');
}

document.querySelector('.modal-actions button[onclick="startSlideshow()"]').addEventListener('click', () => {
    if (slideshowInterval) {
        stopSlideshow();
    } else {
        startSlideshow();
    }
});

function toggleFullscreen() {
    if (!document.fullscreenElement) {
        modalImage.requestFullscreen().catch(err => {
            alert(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
        });
    } else {
        document.exitFullscreen();
    }
}

function toggleThumbnails() {
    thumbnailsContainer.classList.toggle('show-thumbnails');
}

function shareImage() {
    if (navigator.share) {
        navigator.share({
            title: document.title,
            url: images[currentImageIndex]
        }).catch(console.error);
    } else {
        alert('Web Share API is not supported in your browser.');
        const el = document.createElement('textarea');
        el.value = images[currentImageIndex];
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        alert('Image URL copied to clipboard!');
    }
}

modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        closeModal();
    }
});

document.addEventListener('keydown', (e) => {
    if (modal.style.display === 'block') {
        if (e.key === 'Escape') {
            closeModal();
        } else if (e.key === 'ArrowLeft') {
            prevImage();
        } else if (e.key === 'ArrowRight') {
            nextImage();
        }
    }
});
