import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

/**
 * /proyectos — Scroll storytelling premium.
 *
 * Módulos independientes, todos guarded por selector:
 *   - initProyectosHero          → SplitText title char-stagger + watermark parallax
 *   - initProyectosMarquee       → marquee infinito de logos (pause on hover)
 *   - initProyectosFeatured      → pin scrub vertical de destacados
 *   - initProyectosFilter        → FLIP transitions al cambiar categoría
 *   - initProyectosCards         → 3D tilt en hover (desktop only)
 *   - initProyectosCountersExtra → fallback counter para data-counter en estas pages
 *
 * Todos respetan prefers-reduced-motion (early return).
 * Si no estás en /proyectos, los selectores no matchean y los init son no-op.
 */
const reducedMotion = () =>
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

export function initProyectosStorytelling() {
    if (! document.querySelector('[data-proyectos-hero]')) return;

    initProyectosHero();
    initProyectosMarquee();
    initProyectosFeatured();
    initProyectosFilter();
    initProyectosCards();
}

/* ─────────────────────────────────────────────────────────────────────
   1. HERO — char-stagger del título + parallax inverso del watermark
   ───────────────────────────────────────────────────────────────────── */
function initProyectosHero() {
    if (reducedMotion()) return;

    const titleEl = document.querySelector('[data-proyectos-hero-title]');
    const watermarkEl = document.querySelector('[data-proyectos-watermark]');

    // Split del título a nivel character (SplitText DIY — wrap chars en spans)
    if (titleEl) {
        const spans = titleEl.querySelectorAll(':scope > span');
        spans.forEach((span) => {
            const text = span.textContent;
            const wrapped = text.split('').map(ch =>
                ch === ' ' ? '<span class="char-space">&nbsp;</span>'
                           : `<span class="char">${ch}</span>`
            ).join('');
            span.innerHTML = wrapped;
        });
        const chars = titleEl.querySelectorAll('.char');
        gsap.from(chars, {
            opacity: 0,
            y: 60,
            rotateX: -30,
            stagger: 0.024,
            duration: 1.1,
            ease: 'power4.out',
            delay: 0.1,
        });
    }

    // Watermark parallax inverso (sube mientras scrolleas)
    if (watermarkEl) {
        gsap.to(watermarkEl, {
            yPercent: -25,
            opacity: 0,
            ease: 'none',
            scrollTrigger: {
                trigger: '[data-proyectos-hero]',
                start: 'top top',
                end: 'bottom top',
                scrub: true,
            },
        });
    }
}

/* ─────────────────────────────────────────────────────────────────────
   2. MARQUEE — loop infinito GSAP, pause-on-hover
   ───────────────────────────────────────────────────────────────────── */
function initProyectosMarquee() {
    const wrapper = document.querySelector('[data-proyectos-marquee]');
    const track = document.querySelector('[data-proyectos-marquee-track]');
    if (! wrapper || ! track) return;

    if (reducedMotion()) {
        track.style.transform = 'translateX(0)';
        return;
    }

    // Esperar render para tomar ancho real
    requestAnimationFrame(() => {
        const totalWidth = track.scrollWidth / 2;  // duplicado x2 en el partial
        const speed = Math.max(40, totalWidth / 50);  // tiempo en segundos

        gsap.set(track, { x: 0 });
        const tween = gsap.to(track, {
            x: -totalWidth,
            duration: speed,
            ease: 'none',
            repeat: -1,
        });

        // Pause on hover
        wrapper.addEventListener('mouseenter', () => tween.timeScale(0.15));
        wrapper.addEventListener('mouseleave', () => tween.timeScale(1));
    });
}

/* ─────────────────────────────────────────────────────────────────────
   3. FEATURED — Alternating split panels con slide-in direccional.
   (Patrón distinto al pin scrub de /servicios.)
   Cada fila tiene media + copy. Pares: media izq / copy der.
   Impares: media der / copy izq (CSS-flip). Al entrar viewport:
   - media slide desde el borde correspondiente
   - copy slide desde el borde opuesto
   - sutil reveal con clip-path para textura editorial premium
   ───────────────────────────────────────────────────────────────────── */
function initProyectosFeatured() {
    const rows = document.querySelectorAll('[data-proyectos-feat-row]');
    if (rows.length === 0) return;

    if (reducedMotion()) {
        // Estático visible
        rows.forEach((row) => {
            const media = row.querySelector('[data-proyectos-feat-media]');
            const copy  = row.querySelector('[data-proyectos-feat-copy]');
            if (media) { media.style.opacity = '1'; media.style.transform = 'none'; media.style.clipPath = 'inset(0)'; }
            if (copy)  { copy.style.opacity  = '1'; copy.style.transform  = 'none'; }
        });
        return;
    }

    rows.forEach((row) => {
        const isReverse = row.classList.contains('is-reverse');
        const media = row.querySelector('[data-proyectos-feat-media]');
        const copy  = row.querySelector('[data-proyectos-feat-copy]');
        if (! media || ! copy) return;

        // Determinar dirección de entrada según orientación
        // Pares (no reverse): media izq → entra desde la izq | copy der → entra desde la der
        // Impares (reverse):  media der → entra desde la der | copy izq → entra desde la izq
        const mediaFromX = isReverse ? 80 : -80;
        const copyFromX  = isReverse ? -60 : 60;

        // Estado inicial
        gsap.set(media, {
            opacity: 0,
            x: mediaFromX,
            // Clip-path tipo "cortina": revela horizontalmente desde un lado
            clipPath: isReverse ? 'inset(0 0 0 100%)' : 'inset(0 100% 0 0)',
        });
        gsap.set(copy, {
            opacity: 0,
            x: copyFromX,
        });

        // Stats y tags individuales para stagger interno
        const copyChildren = copy.querySelectorAll(
            ':scope > .mt-proy-feat-eyebrow, ' +
            ':scope > .mt-proy-feat-name, ' +
            ':scope > .mt-proy-feat-desc, ' +
            ':scope > .mt-proy-feat-stats, ' +
            ':scope > .mt-proy-feat-tags, ' +
            ':scope > .mt-proy-feat-ctas'
        );
        gsap.set(copyChildren, { opacity: 0, y: 18 });

        ScrollTrigger.create({
            trigger: row,
            start: 'top 75%',
            once: true,
            onEnter: () => {
                const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

                // Media: cortina + slide-in
                tl.to(media, {
                    opacity: 1,
                    x: 0,
                    clipPath: 'inset(0 0% 0 0%)',
                    duration: 1.1,
                }, 0);

                // Copy contenedor: fade-in y x: 0
                tl.to(copy, {
                    opacity: 1,
                    x: 0,
                    duration: 0.9,
                }, 0.15);

                // Stagger interno de los elementos del copy
                tl.to(copyChildren, {
                    opacity: 1,
                    y: 0,
                    duration: 0.7,
                    stagger: 0.08,
                }, 0.35);
            },
        });
    });
}

/* ─────────────────────────────────────────────────────────────────────
   4. FILTER — transiciones suaves al cambiar categoría
   No usa GSAP Flip (no incluido) — implementa FLIP DIY con transform.
   ───────────────────────────────────────────────────────────────────── */
function initProyectosFilter() {
    const filtersWrap = document.querySelector('[data-proyectos-filters]');
    const bento = document.querySelector('[data-proyectos-bento]');
    if (! filtersWrap || ! bento) return;

    const filterBtns = filtersWrap.querySelectorAll('[data-proyectos-filter]');
    const cards = bento.querySelectorAll('[data-proyectos-card]');
    const emptyState = document.querySelector('[data-proyectos-empty]');
    const resetBtn = document.querySelector('[data-proyectos-filter-reset]');

    function applyFilter(category) {
        let visibleCount = 0;

        // FLIP DIY: medir posiciones antes (no necesario sin reorder real).
        // Solo escondemos las que no matchean con fade + scale.

        cards.forEach((card, i) => {
            const matches = category === 'all' || card.dataset.category === category;
            if (matches) {
                card.style.display = '';
                gsap.fromTo(card,
                    { opacity: 0, scale: 0.94, y: 20 },
                    {
                        opacity: 1,
                        scale: 1,
                        y: 0,
                        duration: 0.55,
                        ease: 'power3.out',
                        delay: visibleCount * 0.04,
                        clearProps: 'transform',
                    });
                visibleCount++;
            } else {
                gsap.to(card, {
                    opacity: 0,
                    scale: 0.94,
                    duration: 0.3,
                    ease: 'power2.in',
                    onComplete: () => { card.style.display = 'none'; },
                });
            }
        });

        // Empty state
        if (emptyState) {
            if (visibleCount === 0) {
                emptyState.hidden = false;
                gsap.fromTo(emptyState,
                    { opacity: 0, y: 20 },
                    { opacity: 1, y: 0, duration: 0.4, delay: 0.3 });
            } else {
                emptyState.hidden = true;
            }
        }
    }

    filterBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('is-active'));
            btn.classList.add('is-active');
            applyFilter(btn.dataset.proyectosFilter);
        });
    });

    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('is-active'));
            const allBtn = filtersWrap.querySelector('[data-proyectos-filter="all"]');
            if (allBtn) allBtn.classList.add('is-active');
            applyFilter('all');
        });
    }
}

/* ─────────────────────────────────────────────────────────────────────
   5. CARDS — 3D tilt en hover (desktop only)
   ───────────────────────────────────────────────────────────────────── */
function initProyectosCards() {
    if (reducedMotion()) return;
    if (('ontouchstart' in window) || navigator.maxTouchPoints > 0) return;
    if (window.innerWidth < 1024) return;

    document.querySelectorAll('[data-proyectos-card]').forEach((card) => {
        card.style.transformStyle = 'preserve-3d';
        card.style.transition = 'transform 280ms cubic-bezier(0.22, 1, 0.36, 1)';

        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width  - 0.5;
            const y = (e.clientY - rect.top)  / rect.height - 0.5;
            const rx = (-y * 4).toFixed(2);
            const ry = ( x * 4).toFixed(2);
            card.style.transform = `perspective(900px) rotateX(${rx}deg) rotateY(${ry}deg) translateY(-3px)`;
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
        });
    });
}
