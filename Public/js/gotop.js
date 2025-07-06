document.addEventListener('DOMContentLoaded', () => {
    const gotop = document.getElementById('scrollToTop');
    const progress = document.querySelector('.progress-circle .progress');
    const radius = 18;
    const circumference = 2 * Math.PI * radius;
  
    if (!gotop || !progress) {
      console.warn('❌ scrollToTop elementi və ya progress tapılmadı!');
      return;
    }
  
    progress.style.strokeDasharray = `${circumference}`;
    progress.style.strokeDashoffset = `${circumference}`;
  
    window.addEventListener('scroll', () => {
      const scrollTop = window.scrollY;
      const docHeight = document.documentElement.scrollHeight - window.innerHeight;
      const scrollPercent = scrollTop / docHeight;
  
      const offset = circumference - scrollPercent * circumference;
      progress.style.strokeDashoffset = offset;
  
      if (scrollTop > window.innerHeight / 2) {
        gotop.style.display = 'flex';
      } else {
        gotop.style.display = 'none';
      }
    });
  
    gotop.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  
    console.log('✅ goTop script işə düşdü');
  });
  