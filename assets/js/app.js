// ================= NAV MENU TOGGLE =================
const menuToggle = document.getElementById("menu-toggle");
const menu = document.querySelector("nav ul");
if (menuToggle && menu) {
  menuToggle.addEventListener("click", () => menu.classList.toggle("active"));
}

// ================= THEME TOGGLE (DARK/LIGHT) =================
const themeBtn = document.querySelector(".theme-toggle");
if (themeBtn) {
  themeBtn.addEventListener("click", () => {
    document.body.classList.toggle("dark");
    localStorage.setItem(
      "ifmap_theme",
      document.body.classList.contains("dark") ? "dark" : "light"
    );
  });
  const saved = localStorage.getItem("ifmap_theme");
  if (saved === "dark") document.body.classList.add("dark");
}

// ================= PARALLAX HERO =================
const parallaxLayers = document.querySelectorAll(".hero-layer");
window.addEventListener("scroll", () => {
  const scrolled = window.scrollY;
  parallaxLayers.forEach((layer, idx) => {
    const speed = (idx + 1) * 0.08;
    layer.style.transform = `translateY(${scrolled * speed}px)`;
  });
});

// ================= HERO CAROUSEL =================
const heroCarousel = document.getElementById("hero-carousel");
if (heroCarousel) {
  const track = heroCarousel.querySelector(".hero-track");
  const slides = [...heroCarousel.querySelectorAll(".hero-slide")];
  const left = heroCarousel.querySelector(".hero-arrow.left");
  const right = heroCarousel.querySelector(".hero-arrow.right");
  const dotsWrap = heroCarousel.querySelector(".hero-dots");
  let current = 0;

  // dots
  slides.forEach((_, i) => {
    const d = document.createElement("div");
    d.className = "hero-dot" + (i === 0 ? " active" : "");
    d.dataset.index = i;
    dotsWrap.appendChild(d);
  });

  function goTo(index) {
    current = (index + slides.length) % slides.length;
    track.style.transform = `translateX(${-current * 100}%)`;
    dotsWrap
      .querySelectorAll(".hero-dot")
      .forEach((dot) => dot.classList.remove("active"));
    dotsWrap
      .querySelector(`.hero-dot[data-index="${current}"]`)
      .classList.add("active");
  }

  left.addEventListener("click", () => goTo(current - 1));
  right.addEventListener("click", () => goTo(current + 1));
  dotsWrap.addEventListener("click", (e) => {
    const d = e.target.closest(".hero-dot");
    if (!d) return;
    goTo(parseInt(d.dataset.index, 10));
  });

  // autoplay
  let timer = setInterval(() => goTo(current + 1), 7000);
  heroCarousel.addEventListener("mouseenter", () => clearInterval(timer));
  heroCarousel.addEventListener(
    "mouseleave",
    () => (timer = setInterval(() => goTo(current + 1), 7000))
  );
}

// ================= CAROUSEL (Auto + Drag + Dots) =================
const carousel = document.getElementById("carousel");
if (carousel) {
  const items = [...carousel.querySelectorAll(".carousel-item")];
  // Wrap items in track
  let track = carousel.querySelector(".carousel-track");
  if (!track) {
    track = document.createElement("div");
    track.className = "carousel-track";
    items.forEach((i) => track.appendChild(i));
    carousel.appendChild(track);
  }

  // Dots
  const controls = document.createElement("div");
  controls.className = "carousel-controls";
  items.forEach((_, i) => {
    const dot = document.createElement("div");
    dot.className = "carousel-dot" + (i === 0 ? " active" : "");
    dot.dataset.index = i;
    controls.appendChild(dot);
  });
  carousel.appendChild(controls);

  let current = 0;
  let widthCache = () => items[0].offsetWidth + 32; // item width + gap approximation

  function goTo(index) {
    current = (index + items.length) % items.length;
    const offset = -current * widthCache();
    track.style.transform = `translateX(${offset}px)`;
    controls
      .querySelectorAll(".carousel-dot")
      .forEach((d) => d.classList.remove("active"));
    controls
      .querySelector(`.carousel-dot[data-index="${current}"]`)
      .classList.add("active");
  }

  // Auto-play
  let autoTimer = setInterval(() => goTo(current + 1), 6000);
  carousel.addEventListener("mouseenter", () => clearInterval(autoTimer));
  carousel.addEventListener(
    "mouseleave",
    () => (autoTimer = setInterval(() => goTo(current + 1), 6000))
  );

  // Dots click
  controls.addEventListener("click", (e) => {
    const dot = e.target.closest(".carousel-dot");
    if (!dot) return;
    goTo(parseInt(dot.dataset.index, 10));
  });

  // Drag (pointer events)
  let startX = 0;
  let dragging = false;
  let startTranslate = 0;
  carousel.addEventListener("pointerdown", (e) => {
    dragging = true;
    startX = e.clientX;
    startTranslate = -current * widthCache();
    track.style.transition = "none";
  });
  window.addEventListener("pointermove", (e) => {
    if (!dragging) return;
    const delta = e.clientX - startX;
    track.style.transform = `translateX(${startTranslate + delta}px)`;
  });
  window.addEventListener("pointerup", (e) => {
    if (!dragging) return;
    dragging = false;
    track.style.transition = "";
    const delta = e.clientX - startX;
    if (Math.abs(delta) > 80) {
      goTo(current + (delta < 0 ? 1 : -1));
    } else {
      goTo(current);
    }
  });
  window.addEventListener("resize", () => goTo(current));
}

// ================= SCROLL REVEAL =================
const revealElements = document.querySelectorAll("[data-anim]");
const revealObserver = new IntersectionObserver(
  (entries, obs) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      entry.target.classList.add("fade-in");
      obs.unobserve(entry.target);
    });
  },
  { threshold: 0.15, rootMargin: "0px 0px -40px 0px" }
);
revealElements.forEach((el) => revealObserver.observe(el));

// ================= SMOOTH ANCHOR SCROLL OFFSET =================
document.addEventListener("click", (e) => {
  const a = e.target.closest('a[href^="#"]');
  if (!a) return;
  const id = a.getAttribute("href").substring(1);
  const target =
    document.getElementById(id) || document.querySelector(`[name='${id}']`);
  if (!target) return;
  e.preventDefault();
  const top = target.getBoundingClientRect().top + window.scrollY - 70;
  window.scrollTo({ top, behavior: "smooth" });
});

// ================= OPTIONAL: PERFORMANCE MARK =================
window.addEventListener("load", () => {
  performance.mark("ifmap_loaded");
});

// ================= HERO COUNTERS (ANIMATION) =================
const counters = document.querySelectorAll(".counter");
if (counters.length) {
  const animateCounter = (el) => {
    const target = parseInt(el.getAttribute("data-target"), 10) || 0;
    const duration = 1200; // ms
    const startTime = performance.now();
    function update(now) {
      const progress = Math.min((now - startTime) / duration, 1);
      const value = Math.floor(progress * target);
      el.textContent = value.toLocaleString("fr-FR");
      if (progress < 1) requestAnimationFrame(update);
      else el.textContent = target.toLocaleString("fr-FR");
    }
    requestAnimationFrame(update);
  };
  const counterObserver = new IntersectionObserver(
    (entries, obs) => {
      entries.forEach((e) => {
        if (!e.isIntersecting) return;
        animateCounter(e.target);
        obs.unobserve(e.target);
      });
    },
    { threshold: 0.4 }
  );
  counters.forEach((c) => counterObserver.observe(c));
}

// ================= MODAL COMPONENT =================
const modal = document.getElementById("modal");
if (modal) {
  const backdrop = modal.querySelector(".modal-backdrop");
  const closeBtn = modal.querySelector(".modal-close");
  const contentBox = modal.querySelector(".modal-content");
  function openModal(html) {
    contentBox.innerHTML = html;
    modal.setAttribute("aria-hidden", "false");
    document.body.style.overflow = "hidden";
  }
  function closeModal() {
    modal.setAttribute("aria-hidden", "true");
    document.body.style.overflow = "";
  }
  backdrop.addEventListener("click", closeModal);
  closeBtn.addEventListener("click", closeModal);
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") closeModal();
  });
  document.addEventListener("click", (e) => {
    const trigger = e.target.closest(".open-modal");
    if (!trigger) return;
    e.preventDefault();
    const card = trigger.closest(".card");
    if (card) {
      const title = card.querySelector("h3")?.textContent || "Détails";
      const desc = card.querySelector("p")?.textContent || "";
      openModal(`<h2>${title}</h2><p>${desc}</p>`);
    } else {
      openModal("<p>Contenu indisponible.</p>");
    }
  });
}
