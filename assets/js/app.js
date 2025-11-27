// ================= MENU TOGGLE =================
const menuToggle = document.getElementById("menu-toggle");
const menu = document.querySelector("nav ul");

menuToggle.addEventListener("click", () => {
  menu.classList.toggle("active");
});

// ================= SIMPLE CAROUSEL =================
const carousel = document.getElementById("carousel");

let isDown = false;
let startX;
let scrollLeft;

carousel.addEventListener("mousedown", (e) => {
  isDown = true;
  carousel.classList.add("active");
  startX = e.pageX - carousel.offsetLeft;
  scrollLeft = carousel.scrollLeft;
});
carousel.addEventListener("mouseleave", () => {
  isDown = false;
  carousel.classList.remove("active");
});
carousel.addEventListener("mouseup", () => {
  isDown = false;
  carousel.classList.remove("active");
});
carousel.addEventListener("mousemove", (e) => {
  if (!isDown) return;
  e.preventDefault();
  const x = e.pageX - carousel.offsetLeft;
  const walk = (x - startX) * 2; //scroll-fast
  carousel.scrollLeft = scrollLeft - walk;
});

// ================= FADE ANIM ON SCROLL =================
const faders = document.querySelectorAll("[data-anim]");

const appearOptions = {
  threshold: 0.2,
  rootMargin: "0px 0px -50px 0px",
};

const appearOnScroll = new IntersectionObserver(function (
  entries,
  appearOnScroll
) {
  entries.forEach((entry) => {
    if (!entry.isIntersecting) {
      return;
    } else {
      entry.target.classList.add("fade-in");
      appearOnScroll.unobserve(entry.target);
    }
  });
},
appearOptions);

faders.forEach((fader) => {
  appearOnScroll.observe(fader);
});
