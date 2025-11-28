/* ================= NAV MENU TOGGLE ================= */
const menuToggle = document.getElementById("menu-toggle");
const menu = document.querySelector("nav ul");
if (menuToggle && menu) {
  menuToggle.addEventListener("click", () => menu.classList.toggle("active"));
}

/* ================= THEME TOGGLE (DARK/LIGHT) ================= */
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

/* ================= PARALLAX HERO ================= */
const parallaxLayers = document.querySelectorAll(".hero-layer");
if (parallaxLayers.length) {
  window.addEventListener("scroll", () => {
    const scrolled = window.scrollY;
    parallaxLayers.forEach((layer, idx) => {
      const speed = (idx + 1) * 0.08;
      layer.style.transform = `translateY(${scrolled * speed}px)`;
    });
  });
}

/* ================= HERO CAROUSEL (version propre) ================= */
const heroCarousel = document.getElementById("hero-carousel");
if (heroCarousel) {
  const track = heroCarousel.querySelector(".hero-track");
  const slides = Array.from(track.children);
  const dotsEl = heroCarousel.querySelector(".hero-dots");
  const btnPrev = heroCarousel.querySelector(".hero-arrow.left");
  const btnNext = heroCarousel.querySelector(".hero-arrow.right");

  let index = 0;
  let autoTimer = null;

  function updateHero() {
    track.style.transform = `translateX(${-index * 100}%)`;
    if (dotsEl) {
      [...dotsEl.children].forEach((d, i) =>
        d.classList.toggle("active", i === index)
      );
    }
  }

  // Build dots
  if (dotsEl) {
    dotsEl.innerHTML = "";
    slides.forEach((_, i) => {
      const dot = document.createElement("div");
      dot.className = "hero-dot" + (i === 0 ? " active" : "");
      dot.addEventListener("click", () => {
        index = i;
        updateHero();
      });
      dotsEl.appendChild(dot);
    });
  }

  // Arrows
  btnPrev &&
    btnPrev.addEventListener("click", () => {
      index = (index - 1 + slides.length) % slides.length;
      updateHero();
    });

  btnNext &&
    btnNext.addEventListener("click", () => {
      index = (index + 1) % slides.length;
      updateHero();
    });

  // Auto-play + pause on hover
  const startAuto = () => {
    if (autoTimer) return;
    autoTimer = setInterval(() => {
      index = (index + 1) % slides.length;
      updateHero();
    }, 6000);
  };
  const stopAuto = () => {
    if (!autoTimer) return;
    clearInterval(autoTimer);
    autoTimer = null;
  };

  heroCarousel.addEventListener("mouseenter", stopAuto);
  heroCarousel.addEventListener("mouseleave", startAuto);

  updateHero();
  startAuto();
}

/* ================= GENERIC CAROUSEL ================= */
const carousel = document.getElementById("carousel");
if (carousel) {
  const items = [...carousel.querySelectorAll(".carousel-item")];

  // Si aucun item, ne rien initialiser et masquer la zone carousel
  if (items.length === 0) {
    // Optionnel: masquer le conteneur vide pour éviter un espace inutile
    carousel.style.display = "none";
  } else {
    let track = carousel.querySelector(".carousel-track");
    if (!track) {
      track = document.createElement("div");
      track.className = "carousel-track";
      items.forEach((i) => track.appendChild(i));
      carousel.appendChild(track);
    }

    // Créer les contrôles uniquement si plus d'un item
    let controls = null;
    if (items.length > 1) {
      controls = document.createElement("div");
      controls.className = "carousel-controls";
      items.forEach((_, i) => {
        const dot = document.createElement("div");
        dot.className = "carousel-dot" + (i === 0 ? " active" : "");
        dot.dataset.index = i;
        controls.appendChild(dot);
      });
      carousel.appendChild(controls);
    }

    let current = 0;
    const widthCache = () => (items[0] ? items[0].offsetWidth + 32 : 0);

    function goTo(index) {
      if (!items.length) return;
      current = (index + items.length) % items.length;
      const offset = -current * widthCache();
      track.style.transform = `translateX(${offset}px)`;

      if (controls) {
        controls
          .querySelectorAll(".carousel-dot")
          .forEach((d) => d.classList.remove("active"));
        const activeDot = controls.querySelector(
          `.carousel-dot[data-index="${current}"]`
        );
        activeDot && activeDot.classList.add("active");
      }
    }

    // Auto-play uniquement si plus d'un item
    let autoTimer = null;
    if (items.length > 1) {
      autoTimer = setInterval(() => goTo(current + 1), 6000);
      carousel.addEventListener("mouseenter", () => {
        if (autoTimer) {
          clearInterval(autoTimer);
          autoTimer = null;
        }
      });
      carousel.addEventListener("mouseleave", () => {
        if (!autoTimer) autoTimer = setInterval(() => goTo(current + 1), 6000);
      });
    }

    if (controls) {
      controls.addEventListener("click", (e) => {
        const dot = e.target.closest(".carousel-dot");
        if (!dot) return;
        goTo(parseInt(dot.dataset.index, 10));
      });
    }

    // Dragging (activé uniquement si > 1 item)
    if (items.length > 1) {
      let startX = 0;
      let dragging = false;
      let startTranslate = 0;

      const onPointerDown = (e) => {
        dragging = true;
        startX = e.clientX;
        startTranslate = -current * widthCache();
        track.style.transition = "none";
        // Evite les sélections de texte pendant le drag
        e.preventDefault();
      };

      const onPointerMove = (e) => {
        if (!dragging) return;
        const delta = e.clientX - startX;
        track.style.transform = `translateX(${startTranslate + delta}px)`;
      };

      const endDrag = (e) => {
        if (!dragging) return;
        dragging = false;
        track.style.transition = "";
        const delta = e.clientX - startX;
        if (Math.abs(delta) > 80) {
          goTo(current + (delta < 0 ? 1 : -1));
        } else {
          goTo(current);
        }
      };

      carousel.addEventListener("pointerdown", onPointerDown);
      window.addEventListener("pointermove", onPointerMove);
      window.addEventListener("pointerup", endDrag);
      carousel.addEventListener("pointerleave", endDrag);
      window.addEventListener("blur", endDrag);
    }

    window.addEventListener("resize", () => goTo(current));

    goTo(0);
  }
}

/* ================= SCROLL REVEAL ================= */
const revealElements = document.querySelectorAll("[data-anim]");
if (revealElements.length) {
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
}

/* ================= SMOOTH ANCHOR SCROLL ================= */
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

/* ================= HERO COUNTERS ================= */
const counters = document.querySelectorAll(".counter");
if (counters.length) {
  const animateCounter = (el) => {
    const target = parseInt(el.dataset.target, 10) || 0;
    const duration = 1200;
    const start = performance.now();

    function update(now) {
      const p = Math.min((now - start) / duration, 1);
      el.textContent = Math.floor(p * target).toLocaleString("fr-FR");
      if (p < 1) requestAnimationFrame(update);
    }
    requestAnimationFrame(update);
  };

  const counterObserver = new IntersectionObserver(
    (entries, obs) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;
        animateCounter(entry.target);
        obs.unobserve(entry.target);
      });
    },
    { threshold: 0.4 }
  );

  counters.forEach((c) => counterObserver.observe(c));
}

/* ================= MODAL ================= */
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
  document.addEventListener(
    "keydown",
    (e) => e.key === "Escape" && closeModal()
  );

  document.addEventListener("click", (e) => {
    const trigger = e.target.closest(".open-modal");
    if (!trigger) return;

    e.preventDefault();
    const card = trigger.closest(".card");

    if (card) {
      openModal(`<h2>${card.querySelector("h3")?.textContent || ""}</h2>
                 <p>${card.querySelector("p")?.textContent || ""}</p>`);
    } else {
      openModal("<p>Contenu indisponible.</p>");
    }
  });
}

/* ================= GALLERY ================= */
const galleryGrid = document.getElementById("gallery-grid");
if (galleryGrid) {
  const filterButtons = document.querySelectorAll(".gallery-filters button");
  const categorySelect = document.getElementById("gallery-category");
  const loader = document.getElementById("gallery-loader");

  let loading = false,
    lastPageLoaded = 1,
    endReached = false,
    currentType = "all",
    currentCategory = "";

  function resetGallery() {
    galleryGrid.innerHTML = "";
    lastPageLoaded = 0;
    endReached = false;
  }

  filterButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      filterButtons.forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");
      currentType = btn.dataset.filter;
      resetGallery();
      fetchNextPage(true);
    });
  });

  if (categorySelect) {
    categorySelect.addEventListener("change", () => {
      currentCategory = categorySelect.value;
      resetGallery();
      fetchNextPage(true);
    });
  }

  async function fetchNextPage(initial = false) {
    if (loading || endReached) return;
    loading = true;
    loader.style.display = "block";

    const nextPage = lastPageLoaded + 1;
    const params = new URLSearchParams({
      page: nextPage,
      type: currentType,
      category: currentCategory,
    });

    try {
      const res = await fetch("/gallery-api?" + params.toString());
      const data = await res.json();

      if (!data.items.length) {
        endReached = true;
        return;
      }

      data.items.forEach((m) => {
        const div = document.createElement("div");
        div.className = "gallery-item";
        div.title = m.title;

        if (m.type === "video") {
          div.innerHTML = `
            <div class="video-wrapper">
              <video src="${m.url}" preload="metadata"></video>
              <div class="vplay"></div>
            </div>`;
        } else {
          div.innerHTML = `
            <img loading="lazy" src="${m.url}" alt="${m.title}">
            <div class="overlay"></div>
            <div class="caption">${m.title}</div>`;
        }

        galleryGrid.appendChild(div);
      });

      lastPageLoaded = nextPage;
    } catch (err) {
      console.error(err);
    }

    loader.style.display = "none";
    loading = false;
  }

  // Infinite scroll
  window.addEventListener("scroll", () => {
    if (endReached) return;
    const rect = loader.getBoundingClientRect();
    if (rect.top < window.innerHeight + 200) {
      fetchNextPage();
    }
  });

  // Video controls
  document.addEventListener("click", (e) => {
    const btn = e.target.closest(".vplay");
    if (!btn) return;

    const wrapper = btn.parentElement;
    const vid = wrapper.querySelector("video");
    if (!vid) return;

    if (vid.paused) {
      vid.play();
      btn.style.display = "none";
    } else {
      vid.pause();
      btn.style.display = "";
    }
  });

  // Lightbox via modal
  galleryGrid.addEventListener("click", (e) => {
    const item = e.target.closest(".gallery-item");
    if (!item) return;

    const img = item.querySelector("img");
    if (!img) return;

    const src = img.src;
    const title = item.title || "";

    if (modal) {
      modal.querySelector(".modal-content").innerHTML = `
        <h2>${title}</h2>
        <img src="${src}" style="width:100%;border-radius:12px;max-height:70vh;object-fit:cover">`;

      modal.setAttribute("aria-hidden", "false");
      document.body.style.overflow = "hidden";
    }
  });

  // Initial page load
  fetchNextPage(true);
}
