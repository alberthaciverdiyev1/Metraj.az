const video = document.getElementById('video-frame');
const toggleBtn = document.getElementById('toggle-btn');
const playIcon = document.getElementById('play-icon');
const pauseIcon = document.getElementById('pause-icon');

toggleBtn.addEventListener('click', () => {
    if (video.paused) {
        video.play();
        playIcon.classList.add('d-none');
        pauseIcon.classList.remove('d-none');
    } else {
        video.pause();
        pauseIcon.classList.add('d-none');
        playIcon.classList.remove('d-none');
    }
});
