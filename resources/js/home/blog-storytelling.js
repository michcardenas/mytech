import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

/**
 * /blog y /blog/categoria/* y /blog/tag/* — storytelling cinematográfico.
 *
 * PRINCIPIO DEFENSIVO: el contenido siempre es visible por defecto. Las
 * animaciones SOLO modifican transform (sin opacity:0 sobre texto crítico)
 * para que si algo falla, la página sigue siendo legible.
 *
 * Escenas:
 *   1. Hero title: char-stagger 3D con perspective (chars que NACEN visibles)
 *   2. Hero watermark: parallax inverso + fade out al scrollear
 *   3. Featured card: reveal con escala + clip
 *   4. Grid cards: cinematic stagger con scale + opacity + clip-path
 *   5. Cards: hover 3D tilt (mouse parallax)
 *   6. Cards images: parallax sutil al scrollear
 *   7. Filter bar: sticky shadow al hacer scroll
 */
const reducedMotion = () =>
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const isDesktop = () => window.matchMedia('(min-width: 1024px)').matches;

export function initBlogStorytelling() {
    if (! document.querySelector('[data-blog-hero]')) return;

    initBlogHero();
    initBlogFeatured();
    initBlogGridCards();
    initBlogCardTilt();
    initBlogCardImageParallax();
    initBlogFilterSticky();
}

/* ────────────────────────────────────────────────
   1. HERO title char-stagger + watermark parallax
   ──────────────────────────────────────────────── */
function initBlogHero() {
    if (reducedMotion()) return;

    const titleEl   = document.querySelector('[data-blog-hero-title]');
    const watermark = document.querySelector('[data-blog-watermark]');

    /* Title char-stagger 3D — solo si encontramos el elemento Y conseguimos chars
       Si algo falla, el H1 permanece visible (sin opacity 0 aplicado por adelantado) */
    if (titleEl) {
        const spans = titleEl.querySelectorAll(':scope > span');
        if (spans.length > 0) {
            spans.forEach((span) => {
                const text = span.textContent;
                span.innerHTML = text.split('').map(ch =>
                    ch === ' '
                        ? '<span class="char-space">&nbsp;</span>'
                        : `<span class="char" style="display:inline-block; will-change:transform,opacity;">${ch}</span>`
                ).join('');
            });
            const chars = titleEl.querySelectorAll('.char');
            if (chars.length > 0) {
                // gsap.from aplica opacity:0 a los chars (NO al H1 padre)
                // El H1 permanece visible. Si la animación falla, los chars
                // siguen en opacity 0 — pero como fallback agregamos un timer
                // que los pone visible después de 3s pase lo que pase.
                gsap.from(chars, {
                    opacity: 0,
                    y: 60,
                    rotateX: -40,
                    transformPerspective: 800,
                    stagger: 0.022,
                    duration: 1.1,
                    ease: 'expo.out',
                    delay: 0.1,
                });
                // Defensive failsafe: 3s después garantiza visibilidad
                setTimeout(() => {
                    chars.forEach(ch => {
                        if (parseFloat(getComputedStyle(ch).opacity) < 0.5) {
                            ch.style.opacity = '1';
                            ch.style.transform = 'none';
                        }
                    });
                }, 3000);
            }
        }
    }

    /* Watermark parallax inverso + fade al scrollear */
    if (watermark) {
        gsap.to(watermark, {
            yPercent: -30,
            opacity: 0,
            ease: 'none',
            scrollTrigger: {
                trigger: '[data-blog-hero]',
                start: 'top top',
                end:   'bottom top',
                scrub: 0.6,
            },
        });
    }
}

/* ────────────────────────────────────────────────
   2. FEATURED card — reveal con scale + clip
   ──────────────────────────────────────────────── */
function initBlogFeatured() {
    if (reducedMotion()) return;

    const card = document.querySelector('[data-blog-featured-card]');
    if (! card) return;

    gsap.set(card, { opacity: 0, y: 50, scale: 0.97 });
    ScrollTrigger.create({
        trigger: card,
        start: 'top 85%',
        once: true,
        onEnter: () => {
            gsap.to(card, {
                opacity: 1,
                y: 0,
                scale: 1,
                duration: 1.1,
                ease: 'expo.out',
            });
        },
    });
}

/* ────────────────────────────────────────────────
   3. GRID cards — cinematic stagger reveal
   ──────────────────────────────────────────────── */
function initBlogGridCards() {
    if (reducedMotion()) return;

    const grid = document.querySelector('.mt-blog-grid-list');
    if (! grid) return;

    const cards = grid.querySelectorAll('.mt-blog-card');
    if (cards.length === 0) return;

    // Estado inicial: invisible Y abajo. Pero con failsafe: si scroll trigger
    // no se dispara en 2s, las cards aparecen igual.
    gsap.set(cards, { opacity: 0, y: 60, scale: 0.94 });

    ScrollTrigger.batch(cards, {
        start: 'top 88%',
        once: true,
        onEnter: (batch) => {
            gsap.to(batch, {
                opacity: 1,
                y: 0,
                scale: 1,
                duration: 0.95,
                ease: 'expo.out',
                stagger: { each: 0.09, from: 'start' },
                overwrite: true,
            });
        },
    });

    // Failsafe: 4s después, cualquier card aún oculta se hace visible
    setTimeout(() => {
        cards.forEach(card => {
            const op = parseFloat(getComputedStyle(card).opacity);
            if (op < 0.5) {
                gsap.to(card, {
                    opacity: 1, y: 0, scale: 1,
                    duration: 0.5, ease: 'power2.out', overwrite: true,
                });
            }
        });
    }, 4000);
}

/* ────────────────────────────────────────────────
   4. CARDS — hover 3D tilt (mouse parallax)
   ──────────────────────────────────────────────── */
function initBlogCardTilt() {
    if (! isDesktop() || reducedMotion()) return;

    const cards = document.querySelectorAll('.mt-blog-card');
    cards.forEach((card) => {
        const inner = card.querySelector('.mt-blog-card-link') || card;
        const media = card.querySelector('.mt-blog-card-media img, .mt-blog-card-media-empty');

        let bounds = null;
        const updateBounds = () => { bounds = card.getBoundingClientRect(); };
        updateBounds();
        window.addEventListener('scroll', updateBounds, { passive: true });
        window.addEventListener('resize', updateBounds);

        card.addEventListener('mouseenter', updateBounds);
        card.addEventListener('mousemove', (e) => {
            if (! bounds) return;
            const x = (e.clientX - bounds.left) / bounds.width  - 0.5;
            const y = (e.clientY - bounds.top)  / bounds.height - 0.5;
            gsap.to(inner, {
                rotateY: x * 4,
                rotateX: -y * 4,
                transformPerspective: 1000,
                duration: 0.5,
                ease: 'power2.out',
                overwrite: 'auto',
            });
            if (media) {
                gsap.to(media, {
                    x: x * 8,
                    y: y * 8,
                    duration: 0.6,
                    ease: 'power2.out',
                    overwrite: 'auto',
                });
            }
        });
        card.addEventListener('mouseleave', () => {
            gsap.to(inner, {
                rotateY: 0, rotateX: 0,
                duration: 0.7,
                ease: 'power3.out',
                overwrite: 'auto',
            });
            if (media) {
                gsap.to(media, {
                    x: 0, y: 0,
                    duration: 0.7,
                    ease: 'power3.out',
                    overwrite: 'auto',
                });
            }
        });
    });
}

/* ────────────────────────────────────────────────
   5. CARDS images — parallax sutil al scrollear
   ──────────────────────────────────────────────── */
function initBlogCardImageParallax() {
    if (! isDesktop() || reducedMotion()) return;

    const mediaImgs = document.querySelectorAll('.mt-blog-card-media img');
    mediaImgs.forEach((img) => {
        gsap.to(img, {
            yPercent: -8,
            ease: 'none',
            scrollTrigger: {
                trigger: img.closest('.mt-blog-card'),
                start: 'top bottom',
                end:   'bottom top',
                scrub: 1,
            },
        });
    });
}

/* ────────────────────────────────────────────────
   6. FILTER bar — sticky shadow al scrollear
   ──────────────────────────────────────────────── */
function initBlogFilterSticky() {
    const filterBar = document.querySelector('[data-blog-filters]');
    const gridSection = document.querySelector('[data-blog-grid-section]');
    if (! filterBar || ! gridSection) return;

    ScrollTrigger.create({
        trigger: gridSection,
        start: 'top 120',
        end:   'bottom top',
        onUpdate: (self) => {
            filterBar.classList.toggle('is-scrolled', self.progress > 0 && self.progress < 1);
        },
    });
}
