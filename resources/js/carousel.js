const carousel = document.querySelector('[data-carousel]');

if (carousel) {
    const mainImage = carousel.querySelector('[data-carousel-main]');
    const secondaries = Array.from(carousel.querySelectorAll('[data-carousel-thumb]'));

    function setActiveThumb(activeThumb) {
        secondaries.forEach((thumb) => {
            const isActive = thumb === activeThumb;
            thumb.classList.toggle('is-active', isActive);
            thumb.setAttribute('aria-pressed', String(isActive));
        });
    }

    secondaries.forEach((thumb) => {
        thumb.addEventListener('click', () => {
            const nextSrc = thumb.dataset.carouselSrc;
            const nextAlt = thumb.dataset.carouselAlt || '';

            if (mainImage && nextSrc) {
                mainImage.src = nextSrc;
                mainImage.alt = nextAlt;
            }

            setActiveThumb(thumb);
        });
    });

    if (secondaries.length > 0) {
        setActiveThumb(secondaries[0]);
    }
}

let carouselTimer = 0;
let lastTimestamp = 0;

function animate(timestamp) {
    let deltaTime = timestamp - lastTimestamp;
    lastTimestamp = timestamp;
    if (carousel) {
        carouselTimer += deltaTime;
        if (carouselTimer > 5000) {
            const activeThumb = carousel.querySelector('[data-carousel-thumb].is-active');
            const nextThumb = activeThumb ? activeThumb.nextElementSibling || secondaries[0] : secondaries[0];
            if (nextThumb) {
                nextThumb.click();
            }
            carouselTimer = 0;
        }
    }
    requestAnimationFrame(animate);
}
requestAnimationFrame(animate);

