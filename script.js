document.addEventListener('DOMContentLoaded', () => {
  // ===== Menú móvil =====
  const navToggle = document.querySelector('.nav-toggle');
  const nav = document.querySelector('.nav');

  if (navToggle && nav) {
    navToggle.addEventListener('click', () => {
      const isExpanded = navToggle.getAttribute('aria-expanded') === 'true';
      navToggle.setAttribute('aria-expanded', !isExpanded);
      nav.classList.toggle('is-open');
    });

    // Cerrar el menú al hacer click en un enlace
    nav.addEventListener('click', (e) => {
      if (e.target.matches('.nav__link')) {
        navToggle.setAttribute('aria-expanded', 'false');
        nav.classList.remove('is-open');
      }
    });
  }

  // ===== Lightbox para la galería =====
  const lightbox = document.getElementById('lightbox');
  if (lightbox) {
    const lightboxImg = document.getElementById('lightbox-img');
    const galleryItems = document.querySelectorAll('.gallery-item');
    const closeBtn = document.querySelector('.lightbox-close');

    galleryItems.forEach(item => {
      item.addEventListener('click', e => {
        e.preventDefault();
        lightbox.style.display = 'flex';
        lightboxImg.src = item.href;
      });
    });

    function closeLightbox() {
      lightbox.style.display = 'none';
    }

    closeBtn.addEventListener('click', closeLightbox);

    // Cerrar al hacer clic fuera de la imagen
    lightbox.addEventListener('click', e => {
      if (e.target === lightbox) {
        closeLightbox();
      }
    });
  }
});