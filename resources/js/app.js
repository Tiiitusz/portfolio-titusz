import './projectPagination';

const boat = document.querySelector('.balaton-boat');

let scrollProgress = 0;

function readScroll() {
    const doc = document.documentElement;
    const scrollTop = window.scrollY || doc.scrollTop || 0;
    const maxScroll = Math.max(doc.scrollHeight - window.innerHeight, 1);
    scrollProgress = Math.min(Math.max(scrollTop / maxScroll, 0), 1);
}

window.addEventListener('scroll', readScroll, { passive: true });
window.addEventListener('resize', readScroll);

readScroll();


let maxX = window.innerWidth - (boat ? boat.offsetWidth : 0);
let boatX = -500;
let lastTimestamp = 0;

function animate(timestamp) {
    let deltaTime = timestamp - lastTimestamp;
    lastTimestamp = timestamp;
    if (boat) {
        maxX = window.innerWidth - boat.offsetWidth+500;
        const XNeedsToBe = scrollProgress * Math.max(maxX, -500)-500;
        let deltaX = 0;
        let x = 0;
        if (boatX - XNeedsToBe < 1 && boatX - XNeedsToBe > -1) {
            x = XNeedsToBe;
            boatX = x;
        }
        else {
            deltaX = (XNeedsToBe - boatX) * deltaTime/1000;
            x = boatX + deltaX;
            boatX = x;
        }
        const y = Math.sin(timestamp / 900) * 6;
    
        boat.style.transform = `translate(${x}px, ${y}px)`;
    }
    requestAnimationFrame(animate);
}
requestAnimationFrame(animate);


