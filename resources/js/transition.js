const section = document.querySelector(".scroll-section");
const blackLayer = document.getElementById("blackLayer");
const yellowMask = document.getElementById("yellowMask");

window.addEventListener("scroll", () => {

  const rect = section.getBoundingClientRect();

  const totalScroll = section.offsetHeight - window.innerHeight;

  let progress =
    Math.min(
      Math.max(
        window.scrollY / totalScroll,
        0
      ),
      1
    );

  const percent = progress * 100;

  blackLayer.style.height = percent + "%";
  yellowMask.style.height = percent + "%";
});