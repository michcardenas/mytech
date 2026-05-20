import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

/**
 * /proyectos/{slug} — Página de detalle de proyecto.
 *
 * Animaciones:
 *   1. Hero parallax sutil del watermark del logo
 *   2. Case study rows: cross-reveal direccional (marca + content) por bloque
 *   3. Galería: lightbox con keyboard nav + stagger reveal de tiles
 *   4. Stack chips: reveal con stagger
 *   5. Métricas: counter-style reveal
 *   6. Relacionados: cascade
 *
 * Guarded por selector — solo se activa si encuentra [data-pd-hero].
 */
const reducedMotion = () =>
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

export function initProyectoDetalleStorytelling() {
    if (! document.querySelector('[data-pd-hero]')) return;

    initHeroWatermark();
    initCaseStudyReveal();
    initGalleryLightbox();
    initRelatedTilt();
}

/* ─────────────────────────────────────────────────────────────────────
   1. HERO — parallax del watermark del logo (sube y se desvanece)
   ───────────────────────────────────────────────────────────────────── */
function initHeroWatermark() {
    if (reducedMotion()) return;
    const watermark = document.querySelector('[data-pd-watermark]');
    if (! watermark) return;

    gsap.to(watermark, {
        yPercent: -25,
        opacity: 0,
        ease: 'none',
        scrollTrigger: {
            trigger: '[data-pd-hero]',
            start: 'top top',
            end: 'bottom top',
            scrub: true,
        },
    });
}

/* ─────────────────────────────────────────────────────────────────────
   2. CASE STUDY — Cada row hace cross-reveal: marca slide-from-side,
   contenido slide-up con stagger interno.
   ───────────────────────────────────────────────────────────────────── */
function initCaseStudyReveal() {
    const rows = document.querySelectorAll('[data-pd-case-row]');
    if (rows.length === 0) return;

    if (reducedMotion()) {
        rows.forEach((row) => {
            row.querySelectorAll('[data-pd-case-mark], [data-pd-case-content]').forEach(el => {
                el.style.opacity = '1';
                el.style.transform = 'none';
            });
        });
        return;
    }

    rows.forEach((row) => {
        const isReverse = row.classList.contains('is-reverse');
        const mark    = row.querySelector('[data-pd-case-mark]');
        const content = row.querySelector('[data-pd-case-content]');
        if (! mark || ! content) return;

        const markFromX = isReverse ? 50 : -50;

        gsap.set(mark,    { opacity: 0, x: markFromX });
        gsap.set(content, { opacity: 0, y: 30 });

        // Stagger interno del contenido (p, ul, h3, h2 hijos)
        const contentChildren = content.querySelectorAll(':scope > *');
        gsap.set(contentChildren, { opacity: 0, y: 20 });

        ScrollTrigger.create({
            trigger: row,
            start: 'top 78%',
            once: true,
            onEnter: () => {
                const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });
                tl.to(mark,    { opacity: 1, x: 0, duration: 0.9 }, 0);
                tl.to(content, { opacity: 1, y: 0, duration: 0.7 }, 0.2);
                tl.to(contentChildren, {
                    opacity: 1, y: 0, duration: 0.6, stagger: 0.07,
                }, 0.35);
            },
        });
    });
}

/* ─────────────────────────────────────────────────────────────────────
   3. GALLERY — Lightbox con keyboard nav + ESC para cerrar
   ───────────────────────────────────────────────────────────────────── */
function initGalleryLightbox() {
    const items = document.querySelectorAll('[data-pd-gallery-item]');
    if (items.length === 0) return;

    const lightbox    = document.querySelector('[data-pd-lightbox]');
    const lightboxImg = lightbox?.querySelector('[data-pd-lightbox-img]');
    const closeBtn    = lightbox?.querySelector('[data-pd-lightbox-close]');
    const prevBtn     = lightbox?.querySelector('[data-pd-lightbox-prev]');
    const nextBtn     = lightbox?.querySelector('[data-pd-lightbox-next]');
    const counter     = lightbox?.querySelector('[data-pd-lightbox-counter]');
    if (! lightbox || ! lightboxImg) return;

    const srcs = Array.from(items).map(it => it.dataset.src);
    let currentIdx = 0;

    function open(idx) {
        currentIdx = idx;
        lightboxImg.src = srcs[idx];
        if (counter) counter.textContent = `${idx + 1} / ${srcs.length}`;
        lightbox.classList.add('is-open');
        lightbox.setAttribute('aria-hidden', 'false');
        document.documentElement.style.overflow = 'hidden';
        if (! reducedMotion()) {
            gsap.fromTo(lightbox, { opacity: 0 }, { opacity: 1, duration: 0.25 });
            gsap.fromTo(lightboxImg, { scale: 0.96, opacity: 0 }, { scale: 1, opacity: 1, duration: 0.4, ease: 'power3.out' });
        }
    }
    function close() {
        lightbox.classList.remove('is-open');
        lightbox.setAttribute('aria-hidden', 'true');
        document.documentElement.style.overflow = '';
    }
    function step(delta) {
        const next = (currentIdx + delta + srcs.length) % srcs.length;
        open(next);
    }

    items.forEach((it, i) => {
        it.addEventListener('click', () => open(i));
    });
    closeBtn?.addEventListener('click', close);
    prevBtn?.addEventListener('click', () => step(-1));
    nextBtn?.addEventListener('click', () => step(1));

    // Click fuera de la imagen cierra
    lightbox.addEventListener('click', (e) => {
        if (e.target === lightbox) close();
    });

    // Keyboard
    document.addEventListener('keydown', (e) => {
        if (! lightbox.classList.contains('is-open')) return;
        if (e.key === 'Escape')      close();
        else if (e.key === 'ArrowLeft')  step(-1);
        else if (e.key === 'ArrowRight') step(1);
    });

    // Stagger reveal de los tiles al entrar viewport
    if (! reducedMotion()) {
        gsap.set(items, { opacity: 0, y: 30 });
        ScrollTrigger.create({
            trigger: items[0],
            start: 'top 85%',
            once: true,
            onEnter: () => {
                gsap.to(items, {
                    opacity: 1, y: 0, duration: 0.7, stagger: 0.05, ease: 'power3.out',
                });
            },
        });
    }
}

/* ─────────────────────────────────────────────────────────────────────
   4. RELATED CARDS — 3D tilt en hover (desktop only)
   ───────────────────────────────────────────────────────────────────── */
function initRelatedTilt() {
    if (reducedMotion()) return;
    if (('ontouchstart' in window) || navigator.maxTouchPoints > 0) return;
    if (window.innerWidth < 1024) return;

    document.querySelectorAll('.mt-pd-rel-card').forEach((card) => {
        card.style.transformStyle = 'preserve-3d';
        card.style.transition = 'transform 280ms cubic-bezier(0.22, 1, 0.36, 1)';

        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width  - 0.5;
            const y = (e.clientY - rect.top)  / rect.height - 0.5;
            const rx = (-y * 3).toFixed(2);
            const ry = ( x * 3).toFixed(2);
            card.style.transform = `perspective(900px) rotateX(${rx}deg) rotateY(${ry}deg) translateY(-2px)`;
        });
        card.addEventListener('mouseleave', () => { card.style.transform = ''; });
    });
}
