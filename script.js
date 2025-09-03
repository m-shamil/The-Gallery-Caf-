let slideIndex = 0;

function changeSlide(n) {
    const slides = document.querySelectorAll('.slide');
    slideIndex += n;

    if (slideIndex >= slides.length) {
        slideIndex = 0;
    }

    if (slideIndex < 0) {
        slideIndex = slides.length - 1;
    }

    const slider = document.querySelector('.slides');
    slider.style.transform = `translateX(${-slideIndex * 100}%)`;
}

// Automatically change slides every 3 seconds
setInterval(() => {
    changeSlide(1);
}, 3000);
